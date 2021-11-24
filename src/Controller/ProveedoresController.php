<?php

namespace App\Controller;

use App\Entity\Proveedores;
use App\Entity\Productos;
use App\Entity\ProveedoresProductos;
use App\Form\ProveedoresType;
use App\Repository\ProveedoresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proveedores")
 */
class ProveedoresController extends AbstractController
{
    /**
     * @Route("/", name="proveedores_index", methods={"GET"})
     */
    public function index(ProveedoresRepository $proveedoresRepository): Response
    {
        return $this->render('proveedores/index.html.twig', [
            'proveedores' => $proveedoresRepository->findAllVigentes(),
        ]);
    }

    /**
     * @Route("/new", name="proveedores_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $proveedore = new Proveedores();
        $form = $this->createForm(ProveedoresType::class, $proveedore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proveedore);
            $entityManager->flush();

            return $this->redirectToRoute('proveedores_index');
        }

        return $this->render('proveedores/new.html.twig', [
            'proveedore' => $proveedore,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proveedores_show", methods={"GET"})
     */
    public function show(Proveedores $proveedore): Response
    {
        return $this->render('proveedores/show.html.twig', [
            'proveedore' => $proveedore,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proveedores_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Proveedores $proveedore): Response
    {
        $form = $this->createForm(ProveedoresType::class, $proveedore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proveedores_index');
        }

        return $this->render('proveedores/edit.html.twig', [
            'proveedore' => $proveedore,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proveedores_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Proveedores $proveedore): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proveedore->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $prov = $entityManager->getRepository(Proveedores::class)->find($proveedore->getId());
            $prov->setEliminado(1);
            $entityManager->flush();

            /*
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proveedore);
            $entityManager->flush();
            */
        }

        return $this->redirectToRoute('proveedores_index');
    }

    /**
     * @Route("/{id}/productos", name="proveedores_productos", methods={"GET","POST"})
     */
    public function proveedorProductos(Request $request, Proveedores $proveedore): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(ProveedoresProductos::class)->findBy(['id_proveedor' => $proveedore->getId()]);
        $productos = Array();
        $i = 0;
        foreach($prod as $p){
            $productos[$i]['idProducto'] = $p->getIdProducto()->getId();
            $productos[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            $productos[$i]['codigoBarras'] = $p->getIdProducto()->getCodigoBarras();
            $productos[$i]['categoria'] = $p->getIdProducto()->getCategoria()->getNombre();
            $productos[$i]['costo'] = $p->getCosto();
            $i++;
        }
        return $this->render('proveedores_productos/new.html.twig', [
            'proveedore' => $proveedore,
            'productos' => $productos
        ]);
    }
}
