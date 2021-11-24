<?php

namespace App\Controller;

use App\Entity\ClientesAlmacenes;
use App\Form\ClientesAlmacenesType;
use App\Repository\ClientesAlmacenesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clientes/almacenes")
 */
class ClientesAlmacenesController extends AbstractController
{
    /**
     * @Route("/", name="clientes_almacenes_index", methods={"GET"})
     */
    public function index(ClientesAlmacenesRepository $clientesAlmacenesRepository): Response
    {
        return $this->render('clientes_almacenes/index.html.twig', [
            'clientes_almacenes' => $clientesAlmacenesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="clientes_almacenes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $clientesAlmacene = new ClientesAlmacenes();
        $form = $this->createForm(ClientesAlmacenesType::class, $clientesAlmacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clientesAlmacene);
            $entityManager->flush();

            return $this->redirectToRoute('clientes_almacenes_index');
        }

        return $this->render('clientes_almacenes/new.html.twig', [
            'clientes_almacene' => $clientesAlmacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clientes_almacenes_show", methods={"GET"})
     */
    public function show(ClientesAlmacenes $clientesAlmacene): Response
    {
        return $this->render('clientes_almacenes/show.html.twig', [
            'clientes_almacene' => $clientesAlmacene,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="clientes_almacenes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ClientesAlmacenes $clientesAlmacene): Response
    {
        $form = $this->createForm(ClientesAlmacenesType::class, $clientesAlmacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clientes_almacenes_index');
        }

        return $this->render('clientes_almacenes/edit.html.twig', [
            'clientes_almacene' => $clientesAlmacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clientes_almacenes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ClientesAlmacenes $clientesAlmacene): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clientesAlmacene->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($clientesAlmacene);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clientes_almacenes_index');
    }
}
