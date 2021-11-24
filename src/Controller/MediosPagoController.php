<?php

namespace App\Controller;

use App\Entity\MediosPago;
use App\Form\MediosPagoType;
use App\Repository\MediosPagoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/medios/pago")
 */
class MediosPagoController extends AbstractController
{
    /**
     * @Route("/", name="medios_pago_index", methods={"GET"})
     */
    public function index(MediosPagoRepository $mediosPagoRepository): Response
    {
        return $this->render('medios_pago/index.html.twig', [
            'medios_pagos' => $mediosPagoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="medios_pago_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mediosPago = new MediosPago();
        $form = $this->createForm(MediosPagoType::class, $mediosPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mediosPago);
            $entityManager->flush();

            return $this->redirectToRoute('medios_pago_index');
        }

        return $this->render('medios_pago/new.html.twig', [
            'medios_pago' => $mediosPago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medios_pago_show", methods={"GET"})
     */
    public function show(MediosPago $mediosPago): Response
    {
        return $this->render('medios_pago/show.html.twig', [
            'medios_pago' => $mediosPago,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="medios_pago_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MediosPago $mediosPago): Response
    {
        $form = $this->createForm(MediosPagoType::class, $mediosPago);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('medios_pago_index');
        }

        return $this->render('medios_pago/edit.html.twig', [
            'medios_pago' => $mediosPago,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medios_pago_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MediosPago $mediosPago): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mediosPago->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mediosPago);
            $entityManager->flush();
        }

        return $this->redirectToRoute('medios_pago_index');
    }
}
