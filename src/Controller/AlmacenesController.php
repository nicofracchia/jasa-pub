<?php

namespace App\Controller;

use App\Entity\Almacenes;
use App\Form\AlmacenesType;
use App\Repository\AlmacenesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/almacenes")
 */
class AlmacenesController extends AbstractController
{
    /**
     * @Route("/", name="almacenes_index", methods={"GET"})
     */
    public function index(AlmacenesRepository $almacenesRepository): Response
    {
        return $this->render('almacenes/index.html.twig', [
            'almacenes' => $almacenesRepository->findAllVigentes(),
        ]);
    }

    /**
     * @Route("/new", name="almacenes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $almacene = new Almacenes();
        $form = $this->createForm(AlmacenesType::class, $almacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($almacene);
            $entityManager->flush();

            return $this->redirectToRoute('almacenes_index');
        }

        return $this->render('almacenes/new.html.twig', [
            'almacene' => $almacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="almacenes_show", methods={"GET"})
     */
    public function show(Almacenes $almacene): Response
    {
        return $this->render('almacenes/show.html.twig', [
            'almacene' => $almacene,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="almacenes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Almacenes $almacene): Response
    {
        $form = $this->createForm(AlmacenesType::class, $almacene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('almacenes_index');
        }

        return $this->render('almacenes/edit.html.twig', [
            'almacene' => $almacene,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="almacenes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Almacenes $almacene): Response
    {
        if ($this->isCsrfTokenValid('delete'.$almacene->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $alm = $entityManager->getRepository(Almacenes::class)->find($almacene->getId());
            $alm->setEliminado(1);
            $entityManager->flush();

            /*
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($almacene);
            $entityManager->flush();
            */
        }

        return $this->redirectToRoute('almacenes_index');
    }
}
