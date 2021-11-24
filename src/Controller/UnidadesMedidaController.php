<?php

namespace App\Controller;

use App\Entity\UnidadesMedida;
use App\Form\UnidadesMedidaType;
use App\Repository\UnidadesMedidaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/unidades/medida")
 */
class UnidadesMedidaController extends AbstractController
{
    /**
     * @Route("/", name="unidades_medida_index", methods={"GET"})
     */
    public function index(UnidadesMedidaRepository $unidadesMedidaRepository): Response
    {
        return $this->render('unidades_medida/index.html.twig', [
            'unidades_medidas' => $unidadesMedidaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="unidades_medida_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $unidadesMedida = new UnidadesMedida();
        $form = $this->createForm(UnidadesMedidaType::class, $unidadesMedida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($unidadesMedida);
            $entityManager->flush();

            return $this->redirectToRoute('unidades_medida_index');
        }

        return $this->render('unidades_medida/new.html.twig', [
            'unidades_medida' => $unidadesMedida,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="unidades_medida_show", methods={"GET"})
     */
    public function show(UnidadesMedida $unidadesMedida): Response
    {
        return $this->render('unidades_medida/show.html.twig', [
            'unidades_medida' => $unidadesMedida,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="unidades_medida_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UnidadesMedida $unidadesMedida): Response
    {
        $form = $this->createForm(UnidadesMedidaType::class, $unidadesMedida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('unidades_medida_index');
        }

        return $this->render('unidades_medida/edit.html.twig', [
            'unidades_medida' => $unidadesMedida,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="unidades_medida_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UnidadesMedida $unidadesMedida): Response
    {
        if ($this->isCsrfTokenValid('delete'.$unidadesMedida->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($unidadesMedida);
            $entityManager->flush();
        }

        return $this->redirectToRoute('unidades_medida_index');
    }
}
