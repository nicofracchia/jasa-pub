<?php

namespace App\Controller;

use App\Entity\CotizacionesProductos;
use App\Form\CotizacionesProductosType;
use App\Repository\CotizacionesProductosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cotizaciones/productos")
 */
class CotizacionesProductosController extends AbstractController
{
    /**
     * @Route("/", name="cotizaciones_productos_index", methods={"GET"})
     */
    public function index(CotizacionesProductosRepository $cotizacionesProductosRepository): Response
    {
        return $this->render('cotizaciones_productos/index.html.twig', [
            'cotizaciones_productos' => $cotizacionesProductosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cotizaciones_productos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cotizacionesProducto = new CotizacionesProductos();
        $form = $this->createForm(CotizacionesProductosType::class, $cotizacionesProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cotizacionesProducto);
            $entityManager->flush();

            return $this->redirectToRoute('cotizaciones_productos_index');
        }

        return $this->render('cotizaciones_productos/new.html.twig', [
            'cotizaciones_producto' => $cotizacionesProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cotizaciones_productos_show", methods={"GET"})
     */
    public function show(CotizacionesProductos $cotizacionesProducto): Response
    {
        return $this->render('cotizaciones_productos/show.html.twig', [
            'cotizaciones_producto' => $cotizacionesProducto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cotizaciones_productos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CotizacionesProductos $cotizacionesProducto): Response
    {
        $form = $this->createForm(CotizacionesProductosType::class, $cotizacionesProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cotizaciones_productos_index');
        }

        return $this->render('cotizaciones_productos/edit.html.twig', [
            'cotizaciones_producto' => $cotizacionesProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cotizaciones_productos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CotizacionesProductos $cotizacionesProducto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cotizacionesProducto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cotizacionesProducto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cotizaciones_productos_index');
    }
}
