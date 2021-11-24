<?php

namespace App\Controller;

use App\Entity\ProveedoresAlmacenes;
use App\Form\ProveedoresAlmacenesType;
use App\Repository\ProveedoresAlmacenesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proveedores/almacenes")
 */
class ProveedoresAlmacenesController extends AbstractController
{
    /**
     * @Route("/", name="proveedores_almacenes_index", methods={"GET"})
     */
    public function index(ProveedoresAlmacenesRepository $proveedoresAlmacenesRepository): Response
    {
        return $this->render('proveedores_almacenes/index.html.twig', [
            'proveedores_almacenes' => $proveedoresAlmacenesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="proveedores_almacenes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $proveedoresAlmacene = new ProveedoresAlmacenes();
        $form = $this->createForm(ProveedoresAlmacenesType::class, $proveedoresAlmacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proveedoresAlmacene);
            $entityManager->flush();

            return $this->redirectToRoute('proveedores_almacenes_index');
        }

        return $this->render('proveedores_almacenes/new.html.twig', [
            'proveedores_almacene' => $proveedoresAlmacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proveedores_almacenes_show", methods={"GET"})
     */
    public function show(ProveedoresAlmacenes $proveedoresAlmacene): Response
    {
        return $this->render('proveedores_almacenes/show.html.twig', [
            'proveedores_almacene' => $proveedoresAlmacene,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proveedores_almacenes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProveedoresAlmacenes $proveedoresAlmacene): Response
    {
        $form = $this->createForm(ProveedoresAlmacenesType::class, $proveedoresAlmacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proveedores_almacenes_index');
        }

        return $this->render('proveedores_almacenes/edit.html.twig', [
            'proveedores_almacene' => $proveedoresAlmacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proveedores_almacenes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProveedoresAlmacenes $proveedoresAlmacene): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proveedoresAlmacene->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proveedoresAlmacene);
            $entityManager->flush();
        }

        return $this->redirectToRoute('proveedores_almacenes_index');
    }
}
