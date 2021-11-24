<?php

namespace App\Controller;

use App\Entity\VentasPagos;
use App\Form\VentasPagosType;
use App\Repository\VentasPagosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ventas/pagos")
 */
class VentasPagosController extends AbstractController
{
    /**
     * @Route("/", name="ventas_pagos_index", methods={"GET"})
     */
    public function index(VentasPagosRepository $ventasPagosRepository): Response
    {
        return $this->render('ventas_pagos/index.html.twig', [
            'ventas_pagos' => $ventasPagosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ventas_pagos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ventasPago = new VentasPagos();
        $form = $this->createForm(VentasPagosType::class, $ventasPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ventasPago);
            $entityManager->flush();

            return $this->redirectToRoute('ventas_pagos_index');
        }

        return $this->render('ventas_pagos/new.html.twig', [
            'ventas_pago' => $ventasPago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ventas_pagos_show", methods={"GET"})
     */
    public function show(VentasPagos $ventasPago): Response
    {
        return $this->render('ventas_pagos/show.html.twig', [
            'ventas_pago' => $ventasPago,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ventas_pagos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, VentasPagos $ventasPago): Response
    {
        $form = $this->createForm(VentasPagosType::class, $ventasPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ventas_pagos_index');
        }

        return $this->render('ventas_pagos/edit.html.twig', [
            'ventas_pago' => $ventasPago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ventas_pagos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, VentasPagos $ventasPago): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ventasPago->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ventasPago);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ventas_pagos_index');
    }
}
