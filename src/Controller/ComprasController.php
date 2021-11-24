<?php

namespace App\Controller;

use App\Entity\Compras;
use App\Entity\ComprasProductos;
use App\Entity\Almacenes;
use App\Entity\Proveedores;
use App\Entity\ProveedoresProductos;
use App\Entity\ProductosAlmacenes;
use App\Entity\Productos;
use App\Form\ComprasType;
use App\Repository\ComprasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/compras")
 */
class ComprasController extends AbstractController
{
    /**
     * @Route("/", name="compras_index", methods={"GET"})
     */
    public function index(ComprasRepository $comprasRepository): Response
    {
        return $this->render('compras/index.html.twig', [
            'compras' => $comprasRepository->findAllPendientes(),
        ]);
    }

    /**
     * @Route("/new", name="compras_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('nueva_compra', $request->request->get('_token'))) {
            
            $recepcion = ($request->request->get('compra')['recepcion'] != '') ? new \DateTime($request->request->get('compra')['recepcion']) : null;
            
            $compra = new Compras();
            $compra->setProveedor($entityManager->getRepository(Proveedores::class)->find($request->request->get('compra')['proveedor']));
            $compra->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('compra')['almacen']));
            $compra->setFecha(new \DateTime($request->request->get('compra')['fecha']));
            $compra->setRecepcion($recepcion);
            $compra->setPrecio(0);
            $compra->setEstado($request->request->get('compra')['estado']);
            $compra->setCreador($this->getUser());
            $entityManager->persist($compra);
            $entityManager->flush();
            
            if($request->request->get('guardar') == '1')
                return $this->redirectToRoute('compras_index');
            elseif($request->request->get('guardar') == '2')
                return $this->redirectToRoute('compras_productos', ['id' => $compra->getId()]);
        }

        
        return $this->render('compras/new.html.twig', [
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'proveedores' => $entityManager->getRepository(Proveedores::class)->findAllVigentes()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="compras_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Compras $compra): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('editar_compra_'.$compra->getId(), $request->request->get('_token'))) {
            
            $recepcion = ($request->request->get('compra')['recepcion'] != '') ? new \DateTime($request->request->get('compra')['recepcion']) : null;
            
            $compra->setProveedor($entityManager->getRepository(Proveedores::class)->find($request->request->get('compra')['proveedor']));
            $compra->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('compra')['almacen']));
            $compra->setFecha(new \DateTime($request->request->get('compra')['fecha']));
            $compra->setRecepcion($recepcion);
            $compra->setPrecio(0);
            $compra->setEstado($request->request->get('compra')['estado']);
            $compra->setCreador($this->getUser());
            $entityManager->flush();
            
            if($request->request->get('guardar') == '1')
                return $this->redirectToRoute('compras_index');
            elseif($request->request->get('guardar') == '2')
                return $this->redirectToRoute('compras_productos', ['id' => $compra->getId()]);
        }

        
        return $this->render('compras/edit.html.twig', [
            'compra' => $compra,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'proveedores' => $entityManager->getRepository(Proveedores::class)->findAllVigentes()
        ]);
    }

    /**
     * @Route("/{id}/finalizar", name="compras_finalizar", methods={"GET","POST"})
     */
    public function finalizar(Request $request, Compras $compra, ComprasRepository $comprasRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comprasProductos = $entityManager->getRepository(ComprasProductos::class)->findBy(['compra' => $compra->getId()]);

        if ($this->isCsrfTokenValid('finalizar_compra_'.$compra->getId(), $request->request->get('_token'))) {
           $almacen = $compra->getAlmacen();
            
           foreach($comprasProductos as $p){
                $productoAlmacen = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto(), 'id_almacen' => $almacen]);
                $nuevoStock = $productoAlmacen->getStock() + $p->getCantidad();
                $productoAlmacen->setStock($nuevoStock);
                $entityManager->flush();
           }

           if($compra->getRecepcion() === null)
                $compra->setRecepcion(new \DateTime("now"));
           $compra->setEstado('Finalizado');
           $entityManager->flush();

            return $this->render('compras/index.html.twig', [
                'compras' => $comprasRepository->findAllPendientes(),
            ]);

        }

        $productos = Array();
        $i = 0;
        foreach($comprasProductos as $p){
            $productos[$i]['id'] = $p->getProducto()->getId();
            $productos[$i]['codigo'] = $p->getProducto()->getCodigoBarras();
            $productos[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productos[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getNombre();
            $productos[$i]['cantidad'] = $p->getCantidad();
            $i++;
        }
        
        return $this->render('compras/finalizar.html.twig', [
            'compra' => $compra,
            'productos' => $productos
        ]);
    }

     /**
     * @Route("/{id}/ver", name="compras_ver", methods={"GET","POST"})
     */
    public function ver(Compras $compra): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comprasProductos = $entityManager->getRepository(ComprasProductos::class)->findBy(['compra' => $compra->getId()]);

        $productos = Array();
        $i = 0;
        foreach($comprasProductos as $p){
            $productos[$i]['id'] = $p->getProducto()->getId();
            $productos[$i]['codigo'] = $p->getProducto()->getCodigoBarras();
            $productos[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productos[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getNombre();
            $productos[$i]['cantidad'] = $p->getCantidad();
            $i++;
        }
        
        return $this->render('compras/show.html.twig', [
            'compra' => $compra,
            'productos' => $productos
        ]);
    }

    /**
     * @Route("/{id}", name="compras_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Compras $compra): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compra->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($compra);
            $entityManager->flush();
        }

        return $this->redirectToRoute('compras_index');
    }

    // PRODUCTOS

    /**
     * @Route("/{id}/productos", name="compras_productos", methods={"GET","POST"})
     */
    public function comprasProductos(Request $request, Compras $compra): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(ComprasProductos::class)->findBy(['compra' => $compra->getId()]);
        $productos = Array();
        $i = 0;
        foreach($prod as $p){
            $productos[$i]['idProducto'] = $p->getProducto()->getId();
            $productos[$i]['codigoBarras'] = $p->getProducto()->getCodigoBarras();
            $productos[$i]['categoria'] = $p->getProducto()->getCategoria()->getNombre();
            $productos[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productos[$i]['precio'] = $p->getProducto()->getCosto();
            $productos[$i]['cantidad'] = $p->getCantidad();
            $productos[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }
        return $this->render('compras_productos/new.html.twig', [
            'compra' => $compra,
            'productos' => $productos
        ]);
    }

    /**
     * @Route("/asignar", name="compras_productos_asignar", methods={"GET","POST"})
     */
    public function productosAsignar(Request $request): Response
    {
        $mensaje = '';
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $com = $entityManager->getRepository(Compras::class)->find($request->get('idCompra'));
        $comProd = $entityManager->getRepository(ComprasProductos::class);
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $prod, 'id_almacen' => $com->getAlmacen()]);
        $cant = $request->get('cantidad');

        $cp = $comProd->findOneBy(
            ['producto' => $prod, 'compra' => $com]
        );

        if($cp === null) { 
            
            // Si el producto NO EXISTE lo cargo

            $cp = new ComprasProductos();
            $cp->setProducto($prod);
            $cp->setCompra($com);
            $cp->setCantidad($cant);
            $entityManager->persist($cp);
            $entityManager->flush();
        }else{

            // Si el producto estaba ASIGNADO actualizo cantidad

            $cp->setCantidad($cant);
            $entityManager->flush();
        }

        $producto['id'] = $cp->getProducto()->getId();
        $producto['codigoBarras'] = $cp->getProducto()->getCodigoBarras();
        $producto['categoria'] = $cp->getProducto()->getCategoria()->getNombre();
        $producto['titulo'] = $cp->getProducto()->getTitulo();
        $producto['cantidad'] = $cp->getCantidad();
        $producto['mensaje'] = $mensaje;

        return $this->json($producto);
    }

    /**
     * @Route("/eliminar", name="compras_productos_eliminar", methods={"GET","POST"})
     */
    public function productosEliminar(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $com = $entityManager->getRepository(Compras::class)->find($request->get('idCompra'));
        $pc = $entityManager->getRepository(ComprasProductos::class)->findOneBy(
            [
                'producto' => $prod,
                'compra' => $com
            ]
        );

        $entityManager->remove($pc);
        $entityManager->flush();
        return $this->json('OK!');
    }
}
