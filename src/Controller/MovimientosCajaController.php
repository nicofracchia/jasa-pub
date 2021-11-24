<?php

namespace App\Controller;

use App\Entity\MovimientosCaja;
use App\Form\MovimientosCajaType;
use App\Repository\MovimientosCajaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movimientos/caja")
 */
class MovimientosCajaController extends AbstractController
{
    /**
     * @Route("/", name="movimientos_caja_index", methods={"GET"})
     */
    public function index(MovimientosCajaRepository $movimientosCajaRepository): Response
    {
        return $this->render('movimientos_caja/index.html.twig', [
            'movimientos_cajas' => $movimientosCajaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="movimientos_caja_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $movimientosCaja = new MovimientosCaja();
        $form = $this->createForm(MovimientosCajaType::class, $movimientosCaja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movimientosCaja);
            $entityManager->flush();

            return $this->redirectToRoute('movimientos_caja_index');
        }

        return $this->render('movimientos_caja/new.html.twig', [
            'movimientos_caja' => $movimientosCaja,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movimientos_caja_show", methods={"GET"})
     */
    public function show(MovimientosCaja $movimientosCaja): Response
    {
        return $this->render('movimientos_caja/show.html.twig', [
            'movimientos_caja' => $movimientosCaja,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="movimientos_caja_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MovimientosCaja $movimientosCaja): Response
    {
        $form = $this->createForm(MovimientosCajaType::class, $movimientosCaja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movimientos_caja_index');
        }

        return $this->render('movimientos_caja/edit.html.twig', [
            'movimientos_caja' => $movimientosCaja,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movimientos_caja_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MovimientosCaja $movimientosCaja): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movimientosCaja->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movimientosCaja);
            $entityManager->flush();
        }

        return $this->redirectToRoute('movimientos_caja_index');
    }
}
