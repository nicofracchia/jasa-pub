<?php

namespace App\Controller;

use App\Entity\VentasProductos;
use App\Form\VentasProductosType;
use App\Repository\VentasProductosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ventas/productos")
 */
class VentasProductosController extends AbstractController
{
    /**
     * @Route("/", name="ventas_productos_index", methods={"GET"})
     */
    public function index(VentasProductosRepository $ventasProductosRepository): Response
    {
        return $this->render('ventas_productos/index.html.twig', [
            'ventas_productos' => $ventasProductosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ventas_productos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ventasProducto = new VentasProductos();
        $form = $this->createForm(VentasProductosType::class, $ventasProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ventasProducto);
            $entityManager->flush();

            return $this->redirectToRoute('ventas_productos_index');
        }

        return $this->render('ventas_productos/new.html.twig', [
            'ventas_producto' => $ventasProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ventas_productos_show", methods={"GET"})
     */
    public function show(VentasProductos $ventasProducto): Response
    {
        return $this->render('ventas_productos/show.html.twig', [
            'ventas_producto' => $ventasProducto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ventas_productos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, VentasProductos $ventasProducto): Response
    {
        $form = $this->createForm(VentasProductosType::class, $ventasProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ventas_productos_index');
        }

        return $this->render('ventas_productos/edit.html.twig', [
            'ventas_producto' => $ventasProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ventas_productos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, VentasProductos $ventasProducto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ventasProducto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ventasProducto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ventas_productos_index');
    }
}
