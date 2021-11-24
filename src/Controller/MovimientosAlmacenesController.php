<?php

namespace App\Controller;

use App\Entity\MovimientosAlmacenes;
use App\Entity\MovimientosAlmacenesPedidos;
use App\Entity\MovimientosAlmacenesEnvios;
use App\Entity\MovimientosAlmacenesRecepciones;
use App\Repository\MovimientosAlmacenesRepository;
use App\Repository\MovimientosAlmacenesPedidosRepository;
use App\Repository\MovimientosAlmacenesEnviosRepository;
use App\Repository\MovimientosAlmacenesRecepcionesRepository;
use App\Entity\Almacenes;
use App\Entity\ProductosAlmacenes;
use App\Entity\Productos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movimientos/almacenes")
 */
class MovimientosAlmacenesController extends AbstractController
{
    /**
     * @Route("/", name="movimientos_almacenes_index", methods={"GET"})
     */
    public function index(MovimientosAlmacenesRepository $repo): Response
    {
        return $this->render('movimientos_almacenes/index.html.twig', [
            'movimientos' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/pedido/nuevo", name="movimientos_almacenes_pedido_nuevo", methods={"GET","POST"})
     */
    public function pedidoNuevo(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('nuevo_pedido', $request->request->get('_token'))) {

            $p = new MovimientosAlmacenes();
            $p->setDesde($entityManager->getRepository(Almacenes::class)->find($request->request->get('pedido')['desde']));
            $p->setHacia($entityManager->getRepository(Almacenes::class)->find($request->request->get('pedido')['hacia']));
            $p->setEstado(1);
            $p->setPedido(new \DateTime('now'));
            $entityManager->persist($p);
            $entityManager->flush();
            
            // AGREGO PRODUCTOS AL PEDIDO
            $this->agregarProductosPedido($p, $request->request->get('productoAsignado'));

            return $this->redirectToRoute('movimientos_almacenes_index');
        }
        return $this->render('movimientos_almacenes/pedido_nuevo.html.twig', [
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => Array()
        ]);
    }

    /**
     * @Route("/{id}/pedido/editar", name="movimientos_almacenes_pedido_editar", methods={"GET","POST"})
     */
    public function pedidoEditar(Request $request, MovimientosAlmacenes $movimiento): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        if ($this->isCsrfTokenValid('editar_pedido_'.$movimiento->getId(), $request->request->get('_token'))) {

            $movimiento->setDesde($entityManager->getRepository(Almacenes::class)->find($request->request->get('pedido')['desde']));
            $movimiento->setHacia($entityManager->getRepository(Almacenes::class)->find($request->request->get('pedido')['hacia']));
            $entityManager->flush();
            
            // AGREGO PRODUCTOS AL PEDIDO
            $this->agregarProductosPedido($movimiento, $request->request->get('productoAsignado'));

            return $this->redirectToRoute('movimientos_almacenes_index');
        }

        $productosAsignados = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesPedidos::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignados[$i]['id'] = $p->getProducto()->getId();
            $productosAsignados[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignados[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getDesde()])->getStock();
            $productosAsignados[$i]['cantidad'] = $p->getCantidad();
            $productosAsignados[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        return $this->render('movimientos_almacenes/pedido_editar.html.twig', [
            'pedido' => $movimiento,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => $productosAsignados
        ]);
    }

    /**
     * @Route("/{id}/envio", name="movimientos_almacenes_envio", methods={"GET","POST"})
     */
    public function envio(Request $request, MovimientosAlmacenes $movimiento): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $mensajeErrorProductosStock = '';
        
        if ($this->isCsrfTokenValid('enviar_pedido_'.$movimiento->getId(), $request->request->get('_token'))) {

            $movimiento->setEstado(2);
            $movimiento->setEnvio(new \DateTime('now'));
            $entityManager->flush();
            
            // AGREGO PRODUCTOS AL PEDIDO
            $mensajeErrorProductosStock = $this->agregarProductosEnvio($movimiento, $request->request->get('productoAsignado'));

            if($mensajeErrorProductosStock == '')
                return $this->redirectToRoute('movimientos_almacenes_index');
        }

        $productosAsignadosPedido = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesPedidos::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignadosPedido[$i]['id'] = $p->getProducto()->getId();
            $productosAsignadosPedido[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignadosPedido[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getDesde()])->getStock();
            $productosAsignadosPedido[$i]['cantidad'] = $p->getCantidad();
            $productosAsignadosPedido[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        $productosAsignadosEnvio = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesEnvios::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignadosEnvio[$i]['id'] = $p->getProducto()->getId();
            $productosAsignadosEnvio[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignadosEnvio[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getHacia()])->getStock();
            $productosAsignadosEnvio[$i]['cantidad'] = $p->getCantidad();
            $productosAsignadosEnvio[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        return $this->render('movimientos_almacenes/envio.html.twig', [
            'pedido' => $movimiento,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignadosPedido' => $productosAsignadosPedido,
            'productosAsignadosEnvio' => $productosAsignadosEnvio,
            'mensajeError' => $mensajeErrorProductosStock
        ]);
    }

    /**
     * @Route("/{id}/recepcion", name="movimientos_almacenes_recepcion", methods={"GET","POST"})
     */
    public function recepcion(Request $request, MovimientosAlmacenes $movimiento): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        if ($this->isCsrfTokenValid('confirmar_pedido_'.$movimiento->getId(), $request->request->get('_token'))) {

            $movimiento->setEstado(3);
            $movimiento->setRecepcion(new \DateTime('now'));
            $entityManager->flush();
            
            // ACTUALIZO STOCK EN ALMACEN DESDE
            $this->agregarProductosRecepcion($movimiento, $request->request->get('productoAsignado'));

            return $this->redirectToRoute('movimientos_almacenes_index');
        }

        $productosAsignadosPedido = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesPedidos::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignadosPedido[$i]['id'] = $p->getProducto()->getId();
            $productosAsignadosPedido[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignadosPedido[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getDesde()])->getStock();
            $productosAsignadosPedido[$i]['cantidad'] = $p->getCantidad();
            $productosAsignadosPedido[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        $productosAsignadosEnvio = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesEnvios::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignadosEnvio[$i]['id'] = $p->getProducto()->getId();
            $productosAsignadosEnvio[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignadosEnvio[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getHacia()])->getStock();
            $productosAsignadosEnvio[$i]['cantidad'] = $p->getCantidad();
            $productosAsignadosEnvio[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        return $this->render('movimientos_almacenes/recepcion.html.twig', [
            'pedido' => $movimiento,
            'productosAsignadosPedido' => $productosAsignadosPedido,
            'productosAsignadosEnvio' => $productosAsignadosEnvio
        ]);
    }

    /**
     * @Route("/{id}/detalles", name="movimientos_almacenes_detalles", methods={"GET","POST"})
     */
    public function detalles(Request $request, MovimientosAlmacenes $movimiento): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $productosAsignadosPedido = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesPedidos::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignadosPedido[$i]['id'] = $p->getProducto()->getId();
            $productosAsignadosPedido[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignadosPedido[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getDesde()])->getStock();
            $productosAsignadosPedido[$i]['cantidad'] = $p->getCantidad();
            $productosAsignadosPedido[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        $productosAsignadosEnvio = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesEnvios::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignadosEnvio[$i]['id'] = $p->getProducto()->getId();
            $productosAsignadosEnvio[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignadosEnvio[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getHacia()])->getStock();
            $productosAsignadosEnvio[$i]['cantidad'] = $p->getCantidad();
            $productosAsignadosEnvio[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        $productosAsignadosRecepcion = Array();
        $i = 0;
        foreach($entityManager->getRepository(MovimientosAlmacenesRecepciones::class)->findBy(['movimiento' => $movimiento->getId()]) as $p){
            $productosAsignadosRecepcion[$i]['id'] = $p->getProducto()->getId();
            $productosAsignadosRecepcion[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignadosRecepcion[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $movimiento->getDesde()])->getStock();
            $productosAsignadosRecepcion[$i]['cantidad'] = $p->getCantidad();
            $productosAsignadosRecepcion[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }

        return $this->render('movimientos_almacenes/detalles.html.twig', [
            'pedido' => $movimiento,
            'productosAsignadosPedido' => $productosAsignadosPedido,
            'productosAsignadosEnvio' => $productosAsignadosEnvio,
            'productosAsignadosRecepcion' => $productosAsignadosRecepcion
        ]);
    }

    // PRODUCTOS
    /**
     * @Route("/productos/pedido", name="pedidos_productos", methods={"GET","POST"})
     */
    public function pedidosProductos(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $request->get('idProducto'), 'id_almacen' => $request->get('idAlmacen')]);

        $res['id'] = $prodAlm->getIdProducto()->getId();
        $res['titulo'] = $prodAlm->getIdProducto()->getTitulo();
        $res['costo'] = $prodAlm->getIdProducto()->getCosto();
        $res['precio'] = $prodAlm->getIdProducto()->getPrecioFinal();
        $res['stock'] = $prodAlm->getStock();
        $res['um'] = $prodAlm->getIdProducto()->getIdUnidadMedida()->getCorto();

        return $this->json($res);
    }

    public function agregarProductosPedido(MovimientosAlmacenes $pedido, $prodCant){
        $entityManager = $this->getDoctrine()->getManager();

        if($prodCant === null)
            $prodCant = Array();

        // ELIMINO LOS PRODUCTOS VIEJOS PARA AGREGAR LOS NUEVOS
        $entityManager->getRepository(MovimientosAlmacenesPedidos::class)->eliminarProductosPedido($pedido->getId());
        
        $pc = Array();
        $i = 0;
        foreach(array_keys($prodCant) as $idProd){
            $producto = $entityManager->getRepository(Productos::class)->find($idProd);
            
            if($prodCant[$idProd] == '')
                $prodCant[$idProd] = 0;

            $mapp = new MovimientosAlmacenesPedidos();
            $mapp->setMovimiento($pedido);
            $mapp->setProducto($producto);
            $mapp->setCantidad($prodCant[$idProd]);
            $entityManager->persist($mapp);
            $entityManager->flush();
        }
    }

    /**
     * @Route("/productos/enviar", name="envios_productos", methods={"GET","POST"})
     */
    public function enviosProductos(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $request->get('idProducto'), 'id_almacen' => $request->get('idAlmacen')]);

        $res['id'] = $prodAlm->getIdProducto()->getId();
        $res['titulo'] = $prodAlm->getIdProducto()->getTitulo();
        $res['costo'] = $prodAlm->getIdProducto()->getCosto();
        $res['precio'] = $prodAlm->getIdProducto()->getPrecioFinal();
        $res['stock'] = $prodAlm->getStock();
        $res['um'] = $prodAlm->getIdProducto()->getIdUnidadMedida()->getCorto();

        return $this->json($res);
    }

    public function agregarProductosEnvio(MovimientosAlmacenes $envio, $prodCant){
        $entityManager = $this->getDoctrine()->getManager();

        $mensajeError = '';

        if($prodCant === null)
            $prodCant = Array();

        // DEVUELVO EL STOCK SI YA HABIA PRODUCT CARGADOS
        $productosEnvio = $entityManager->getRepository(MovimientosAlmacenesEnvios::class)->findBy(['movimiento' => $envio->getId()]);
        foreach($productosEnvio as $p){
            $stockEnvio = $p->getCantidad();
            $productoAlmacen = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $envio->getHacia()]);
            $stockProductoAlmacen = $productoAlmacen->getStock();
            $stockNuevo = $stockEnvio + $stockProductoAlmacen;
            $productoAlmacen->setStock($stockNuevo);
            $entityManager->flush();
        }
        // ELIMINO LOS PRODUCTOS VIEJOS PARA AGREGAR LOS NUEVOS
        $entityManager->getRepository(MovimientosAlmacenesEnvios::class)->eliminarProductosEnvio($envio->getId());
        
        // CARGO LOS PRODUTOS NUEVOS        
        $pc = Array();
        $i = 0;
        foreach(array_keys($prodCant) as $idProd){
            $producto = $entityManager->getRepository(Productos::class)->find($idProd);
            
            if($prodCant[$idProd] == '')
                $prodCant[$idProd] = 0;

            // RESTO EL STOCK DEL ALMACEN, RESULTDO MAYOR Q 0 CARGO LOS PRODUCTO, SINO LO CANCELO Y MANDOMENSAJ DE ERROR
            $productoAlmacen = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $producto->getId(), 'id_almacen' => $envio->getHacia()]);
            $stockProductoAlmacen = $productoAlmacen->getStock();
            $stockNuevo = $stockProductoAlmacen - $prodCant[$idProd];

            if($stockNuevo < 0){
                $mensajeError .= "No hay suficiente stock del producto: ".$producto->getTitulo().".<br/>";
                $mensajeError .= "&nbsp;&nbsp;&nbsp;Cantidad solicitada: ".$prodCant[$idProd].$producto->getIdUnidadMedida()->getCorto()."<br/>";
                $mensajeError .= "&nbsp;&nbsp;&nbsp;Stock actual: ".$stockProductoAlmacen.$producto->getIdUnidadMedida()->getCorto()."<br/><br/><br/>";
            }else{
                $productoAlmacen->setStock($stockNuevo);
                $mapp = new MovimientosAlmacenesEnvios();
                $mapp->setMovimiento($envio);
                $mapp->setProducto($producto);
                $mapp->setCantidad($prodCant[$idProd]);
                $entityManager->persist($mapp);
                $entityManager->flush();
            }
        }

        return $mensajeError;
    }

    public function agregarProductosRecepcion(MovimientosAlmacenes $envio, $prodCant){
        $entityManager = $this->getDoctrine()->getManager();

        if($prodCant === null)
            $prodCant = Array();

        // CARGO LOS PRODUCTOS NUEVOS Y ACTULIZO EL STOCK
        $pc = Array();
        $i = 0;
        foreach(array_keys($prodCant) as $idProd){
            $producto = $entityManager->getRepository(Productos::class)->find($idProd);
            
            if($prodCant[$idProd] == '')
                $prodCant[$idProd] = 0;

            // SUMO EL STOCK E EL ALMACEN
            $productoAlmacen = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $producto->getId(), 'id_almacen' => $envio->getDesde()]);
            $stockProductoAlmacen = $productoAlmacen->getStock();
            $stockNuevo = $stockProductoAlmacen + $prodCant[$idProd];

            $productoAlmacen->setStock($stockNuevo);
            $mapp = new MovimientosAlmacenesRecepciones();
            $mapp->setMovimiento($envio);
            $mapp->setProducto($producto);
            $mapp->setCantidad($prodCant[$idProd]);
            $entityManager->persist($mapp);
            $entityManager->flush();
        }
    }
}
