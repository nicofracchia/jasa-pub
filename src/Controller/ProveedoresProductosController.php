<?php

namespace App\Controller;

use App\Entity\ProveedoresProductos;
use App\Entity\Productos;
use App\Entity\Proveedores;
use App\Form\ProveedoresProductosType;
use App\Repository\ProveedoresProductosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proveedores/productos")
 */
class ProveedoresProductosController extends AbstractController
{
    /**
     * @Route("/", name="proveedores_productos_index", methods={"GET"})
     */
    public function index(ProveedoresProductosRepository $proveedoresProductosRepository): Response
    {
        return $this->render('proveedores_productos/index.html.twig', [
            'proveedores_productos' => $proveedoresProductosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/asignar", name="proveedores_productos_asignar", methods={"GET","POST"})
     */
    public function productosAsignar(Request $request, ProveedoresProductosRepository $proveedoresProductosRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $prov = $entityManager->getRepository(Proveedores::class)->find($request->get('idProveedor'));
        $precio = $request->get('precio');

        $pp = $proveedoresProductosRepository->findOneBy(
            ['id_producto' => $prod, 'id_proveedor' => $prov]
        );

        if($pp === null) { // Si el producto no estaba asignado lo cargo.
            $pp = new ProveedoresProductos();
            $pp->setIdProducto($prod);
            $pp->setIdProveedor($prov);
            $pp->setCosto($precio);
            $entityManager->persist($pp);
            $entityManager->flush();
        }else{ // Si el producto estaba asignado actualizo el precio.
            $pp->setIdProducto($prod);
            $pp->setIdProveedor($prov);
            $pp->setCosto($precio);
            $entityManager->flush();
        }

        $producto['id'] = $pp->getIdProducto()->getId();
        $producto['codigoBarras'] = $pp->getIdProducto()->getCodigoBarras();
        $producto['categoria'] = $pp->getIdProducto()->getCategoria()->getNombre();
        $producto['titulo'] = $pp->getIdProducto()->getTitulo();
        $producto['precio'] = $pp->getCosto();

        return $this->json($producto);
    }

    /**
     * @Route("/asignados", name="proveedores_productos_asignados", methods={"GET","POST"})
     */
    public function productosAsignados(Request $request, ProveedoresProductosRepository $proveedoresProductosRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prov = $entityManager->getRepository(Proveedores::class)->find($request->get('idProveedor'));
        $pp = $proveedoresProductosRepository->findBy(
            ['id_proveedor' => $prov]
        );

        $i = 0;
        foreach ($pp as $p) {
            $data[$i]['id'] = $p->getId();
            $data[$i]['idProducto'] = $p->getIdProducto()->getId();
            $data[$i]['categoria'] = $p->getIdProducto()->getCategoria()->getNombre();
            $data[$i]['barras'] = $p->getIdProducto()->getCodigoBarras();
            $data[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            $data[$i]['precio'] = $p->getCosto();
            $i++;
        }

        return $this->json($data);
    }

    /**
     * @Route("/new", name="proveedores_productos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $proveedoresProducto = new ProveedoresProductos();
        $form = $this->createForm(ProveedoresProductosType::class, $proveedoresProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proveedoresProducto);
            $entityManager->flush();

            return $this->redirectToRoute('proveedores_productos_index');
        }

        return $this->render('proveedores_productos/new.html.twig', [
            'proveedores_producto' => $proveedoresProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proveedores_productos_show", methods={"GET"})
     */
    public function show(ProveedoresProductos $proveedoresProducto): Response
    {
        return $this->render('proveedores_productos/show.html.twig', [
            'proveedores_producto' => $proveedoresProducto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proveedores_productos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProveedoresProductos $proveedoresProducto): Response
    {
        $form = $this->createForm(ProveedoresProductosType::class, $proveedoresProducto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proveedores_productos_index');
        }

        return $this->render('proveedores_productos/edit.html.twig', [
            'proveedores_producto' => $proveedoresProducto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/eliminar", name="proveedores_productos_eliminar", methods={"POST"})
     */
    public function delete(Request $request, ProveedoresProductosRepository $proveedoresProductosRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $prov = $entityManager->getRepository(Proveedores::class)->find($request->get('idProveedor'));
        $pp = $proveedoresProductosRepository->findOneBy(
            [
                'id_producto' => $prod,
                'id_proveedor' => $prov
            ]
        );
        $entityManager->remove($pp);
        $entityManager->flush();
        return $this->json('OK!');
    }
}
