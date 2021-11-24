<?php

namespace App\Controller;

use App\Entity\CombosProductos;
use App\Form\CombosProductos1Type;
use App\Repository\CombosProductosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/combos/productos")
 */
class CombosProductosController extends AbstractController
{
   

    /**
     * @Route("/{id}", name="combos_productos_show", methods={"GET"})
     */
    public function show(CombosProductos $combosProducto): Response
    {
        return $this->render('combos_productos/show.html.twig', [
            'combos_producto' => $combosProducto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="combos_productos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CombosProductos $combosProducto): Response
    {
        $form = $this->createForm(CombosProductos1Type::class, $combosProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('combos_productos_index');
        }

        return $this->render('combos_productos/edit.html.twig', [
            'combos_producto' => $combosProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="combos_productos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CombosProductos $combosProducto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$combosProducto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($combosProducto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('combos_productos_index');
    }
}
