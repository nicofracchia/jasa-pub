<?php

namespace App\Controller;

use App\Entity\AjustesStock;
use App\Entity\Productos;
use App\Entity\UnidadesMedida;
use App\Entity\MotivosAjustesStock;
use App\Entity\Usuarios;
use App\Entity\Almacenes;
use App\Entity\ProductosAlmacenes;
use App\Form\AjustesStockType;
use App\Repository\AjustesStockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ajustes/stock")
 */
class AjustesStockController extends AbstractController
{
    /**
     * @Route("/", name="ajustes_stock_index", methods={"GET"})
     */
    public function index(AjustesStockRepository $ajustesStockRepository): Response
    {
        return $this->render('ajustes_stock/index.html.twig', [
            'ajustes_stocks' => $ajustesStockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id_producto}/nuevo", name="ajustes_stock_nuevo", methods={"GET","POST"})
     */
    public function nuevo(Request $request, $id_producto): Response
    {
        if ($this->isCsrfTokenValid('nuevoAjusteDeStock', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            // MOTIVO
            $mot = $entityManager->getRepository(MotivosAjustesStock::class)->find($request->request->get('ajuste')['motivo']);

            // USUARIO
            $usu = $entityManager->getRepository(Usuarios::class)->find(1); // -----> ELIMINAR ESTE Y DEJAR EL DE ABAJO!!!
            //$usu = $this->getUser();

            // PRODUCTO
            $prod = $entityManager->getRepository(Productos::class)->find($id_producto);

            // ALMACEN
            if(in_array('ROLE_ADMIN', $this->getUser()->getRoles()))
                $alm = $entityManager->getRepository(Almacenes::class)->find($request->request->get('ajuste')['almacen']);
            else
                $alm = $this->getUser()->getAlmacen();

            
            // GUARDO EL AJUSTE RELACIONADO AL PRODUCTO
            $ajustar = new AjustesStock();
            $ajustar->setFecha(new \DateTime());
            $ajustar->setAlmacen($alm);
            $ajustar->setCantidad($request->request->get('ajuste')['merma']);
            $ajustar->setMotivo($mot);
            $ajustar->setObservaciones($request->request->get('ajuste')['observaciones']);
            $ajustar->setUsuario($usu);
            $ajustar->setStockAnterior($request->request->get('ajuste')['stock_actual']);
            $ajustar->setProducto($prod);
            $entityManager->persist($ajustar);
            $entityManager->flush();

            // ACTUALIZO EL STOCK DEL PRODUCTO EN LA TABLA
            $stockAlmacen = $entityManager->getRepository(ProductosAlmacenes::class)->findBy(['id_producto' => $prod, 'id_almacen' => $alm]);
            $stockAlmacen[0]->setStock($request->request->get('ajuste')['stock_real']);
            $entityManager->flush();
            /*
            $prod->setStockActual($request->request->get('ajuste')['stock_real']);
            $entityManager->flush();
            */

            return $this->redirectToRoute('productos_index');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $producto = $entityManager->getRepository(Productos::class)->find($id_producto);
        $unidad_medida = $entityManager->getRepository(UnidadesMedida::class)->find($producto->getIdUnidadMedida());
        $motivos = $entityManager->getRepository(MotivosAjustesStock::class)->findAll();
        $listado_ajustes = $entityManager->getRepository(AjustesStock::class)->listadoAjustesPorProducto($id_producto);
        $almacenesProducto = Array();
        $i = 0;
        foreach($producto->getProductosAlmacenes() as $a){
            $almacenesProducto[$i]['id'] = $a->getIdAlmacen()->getId();
            $almacenesProducto[$i]['nombre'] = $a->getIdAlmacen()->getNombre();
            $almacenesProducto[$i]['stock'] = $a->getStock();
            $i++;
        }
        
        return $this->render('ajustes_stock/new.html.twig', [
            'producto' => $producto,
            'unidadMedida' => $unidad_medida,
            'motivos' => $motivos,
            'listadoAjustes' => $listado_ajustes,
            'almacenes' => $almacenesProducto
        ]);
    }

    /**
     * @Route("/new", name="ajustes_stock_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ajustesStock = new AjustesStock();
        $form = $this->createForm(AjustesStockType::class, $ajustesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ajustesStock);
            $entityManager->flush();

            return $this->redirectToRoute('ajustes_stock_index');
        }

        return $this->render('ajustes_stock/new.html.twig', [
            'ajustes_stock' => $ajustesStock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ajustes_stock_show", methods={"GET"})
     */
    public function show(AjustesStock $ajustesStock): Response
    {
        return $this->render('ajustes_stock/show.html.twig', [
            'ajustes_stock' => $ajustesStock,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ajustes_stock_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AjustesStock $ajustesStock): Response
    {
        $form = $this->createForm(AjustesStockType::class, $ajustesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ajustes_stock_index');
        }

        return $this->render('ajustes_stock/edit.html.twig', [
            'ajustes_stock' => $ajustesStock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ajustes_stock_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AjustesStock $ajustesStock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ajustesStock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ajustesStock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ajustes_stock_index');
    }

    /**
     * @Route("/motivo/agregar", name="motivos_ajustes_stock_modal", methods={"GET","POST"})
     */
    public function modalMotivosAjustesStock(Request $request): Response
    {
        if ($this->isCsrfTokenValid('nuevo_motivo', $request->request->get('token'))) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $motivo = new MotivosAjustesStock();
            $motivo->setNombre($request->request->get('motivo'));
            $motivo->setTipo((int)$request->request->get('tipo'));

            $entityManager->persist($motivo);
            $entityManager->flush();
            
            $c = Array();
            $c['id'] = $motivo->getId();
            $c['motivo'] = $motivo->getNombre();
            $c['tipo'] = $motivo->getTipo();

            return $this->json($c);
        }
        return $this->render('generales/modalMotivoAjusteStock.html.twig');
    }
}
