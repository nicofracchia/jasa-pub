<?php

namespace App\Controller;

use App\Entity\MotivosAjustesStock;
use App\Form\MotivosAjustesStockType;
use App\Repository\MotivosAjustesStockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/motivos/ajustes/stock")
 */
class MotivosAjustesStockController extends AbstractController
{
    /**
     * @Route("/", name="motivos_ajustes_stock_index", methods={"GET"})
     */
    public function index(MotivosAjustesStockRepository $motivosAjustesStockRepository): Response
    {
        return $this->render('motivos_ajustes_stock/index.html.twig', [
            'motivos_ajustes_stocks' => $motivosAjustesStockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="motivos_ajustes_stock_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $motivosAjustesStock = new MotivosAjustesStock();
        $form = $this->createForm(MotivosAjustesStockType::class, $motivosAjustesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($motivosAjustesStock);
            $entityManager->flush();

            return $this->redirectToRoute('motivos_ajustes_stock_index');
        }

        return $this->render('motivos_ajustes_stock/new.html.twig', [
            'motivos_ajustes_stock' => $motivosAjustesStock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="motivos_ajustes_stock_show", methods={"GET"})
     */
    public function show(MotivosAjustesStock $motivosAjustesStock): Response
    {
        return $this->render('motivos_ajustes_stock/show.html.twig', [
            'motivos_ajustes_stock' => $motivosAjustesStock,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="motivos_ajustes_stock_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MotivosAjustesStock $motivosAjustesStock): Response
    {
        $form = $this->createForm(MotivosAjustesStockType::class, $motivosAjustesStock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('motivos_ajustes_stock_index');
        }

        return $this->render('motivos_ajustes_stock/edit.html.twig', [
            'motivos_ajustes_stock' => $motivosAjustesStock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="motivos_ajustes_stock_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MotivosAjustesStock $motivosAjustesStock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$motivosAjustesStock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($motivosAjustesStock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('motivos_ajustes_stock_index');
    }
}
