<?php

namespace App\Controller;

use App\Entity\ProductosAlmacenes;
use App\Form\ProductosAlmacenesType;
use App\Repository\ProductosAlmacenesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/productos/almacenes")
 */
class ProductosAlmacenesController extends AbstractController
{
    /**
     * @Route("/", name="productos_almacenes_index", methods={"GET"})
     */
    public function index(ProductosAlmacenesRepository $productosAlmacenesRepository): Response
    {
        return $this->render('productos_almacenes/index.html.twig', [
            'productos_almacenes' => $productosAlmacenesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="productos_almacenes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productosAlmacene = new ProductosAlmacenes();
        $form = $this->createForm(ProductosAlmacenesType::class, $productosAlmacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productosAlmacene);
            $entityManager->flush();

            return $this->redirectToRoute('productos_almacenes_index');
        }

        return $this->render('productos_almacenes/new.html.twig', [
            'productos_almacene' => $productosAlmacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="productos_almacenes_show", methods={"GET"})
     */
    public function show(ProductosAlmacenes $productosAlmacene): Response
    {
        return $this->render('productos_almacenes/show.html.twig', [
            'productos_almacene' => $productosAlmacene,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="productos_almacenes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductosAlmacenes $productosAlmacene): Response
    {
        $form = $this->createForm(ProductosAlmacenesType::class, $productosAlmacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('productos_almacenes_index');
        }

        return $this->render('productos_almacenes/edit.html.twig', [
            'productos_almacene' => $productosAlmacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="productos_almacenes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductosAlmacenes $productosAlmacene): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productosAlmacene->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productosAlmacene);
            $entityManager->flush();
        }

        return $this->redirectToRoute('productos_almacenes_index');
    }
}
