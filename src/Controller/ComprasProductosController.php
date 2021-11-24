<?php

namespace App\Controller;

use App\Entity\ComprasProductos;
use App\Form\ComprasProductosType;
use App\Repository\ComprasProductosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/compras/productos")
 */
class ComprasProductosController extends AbstractController
{
    /**
     * @Route("/", name="compras_productos_index", methods={"GET"})
     */
    public function index(ComprasProductosRepository $comprasProductosRepository): Response
    {
        return $this->render('compras_productos/index.html.twig', [
            'compras_productos' => $comprasProductosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="compras_productos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $comprasProducto = new ComprasProductos();
        $form = $this->createForm(ComprasProductosType::class, $comprasProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comprasProducto);
            $entityManager->flush();

            return $this->redirectToRoute('compras_productos_index');
        }

        return $this->render('compras_productos/new.html.twig', [
            'compras_producto' => $comprasProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compras_productos_show", methods={"GET"})
     */
    public function show(ComprasProductos $comprasProducto): Response
    {
        return $this->render('compras_productos/show.html.twig', [
            'compras_producto' => $comprasProducto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="compras_productos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ComprasProductos $comprasProducto): Response
    {
        $form = $this->createForm(ComprasProductosType::class, $comprasProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compras_productos_index');
        }

        return $this->render('compras_productos/edit.html.twig', [
            'compras_producto' => $comprasProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compras_productos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ComprasProductos $comprasProducto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comprasProducto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comprasProducto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('compras_productos_index');
    }
}
