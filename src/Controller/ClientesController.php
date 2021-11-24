<?php

namespace App\Controller;

use App\Entity\Clientes;
use App\Form\ClientesType;
use App\Repository\ClientesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clientes")
 */
class ClientesController extends AbstractController
{
    /**
     * @Route("/", name="clientes_index", methods={"GET"})
     */
    public function index(ClientesRepository $clientesRepository): Response
    {
        return $this->render('clientes/index.html.twig', [
            'clientes' => $clientesRepository->findAllVigentes(),
        ]);
    }

    /**
     * @Route("/new", name="clientes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cliente = new Clientes();
        $form = $this->createForm(ClientesType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cliente);
            $entityManager->flush();

            return $this->redirectToRoute('clientes_index');
        }

        return $this->render('clientes/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modal", name="clientes_modal", methods={"GET","POST"})
     */
    public function modal(Request $request): Response
    {
        if ($this->isCsrfTokenValid('nuevo_cliente', $request->request->get('token'))) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $CUIT = ($request->request->get('cuit') != '') ? $request->request->get('cuit') : 0;

            $cliente = new Clientes();
            $cliente->setNombre($request->request->get('nombre'));
            $cliente->setApellido($request->request->get('apellido'));
            $cliente->setMail($request->request->get('mail'));
            $cliente->setDni($request->request->get('dni'));
            $cliente->setTelefono($request->request->get('telefono'));
            $cliente->setDireccion($request->request->get('direccion'));
            $cliente->setHabilitado(1);
            $cliente->setEliminado(0);
            $cliente->setCuit($CUIT);
            $cliente->setRazonSocial($request->request->get('razon_social'));
            $cliente->setCuentaCorriente(($request->request->get('cuenta_corriente') == "true") ? 1 : 0);

            $entityManager->persist($cliente);
            $entityManager->flush();
            
            $c = Array();
            $c['id'] = $cliente->getId();
            $c['nombre'] = $cliente->getNombre();
            $c['apellido'] = $cliente->getApellido();
            $c['mail'] = $cliente->getMail();
            $c['dni'] = $cliente->getDni();
            $c['telefono'] = $cliente->getTelefono();
            $c['direccion'] = $cliente->getDireccion();
            $c['cuit'] = $cliente->getCuit();
            $c['rs'] = $cliente->getRazonSocial();
            $c['cc'] = $cliente->getCuentaCorriente();

            return $this->json($c);
        }
        return $this->render('generales/modalCliente.html.twig');
    }

    /**
     * @Route("/buscar", name="clientes_buscar", methods={"GET","POST"})
     */
    public function buscar(Request $request, ClientesRepository $clientesRepository): Response
    {
        $busqueda = explode(' ',$request->request->get('busqueda'));
        $clientes = $clientesRepository->findAllBusqueda($busqueda);
        return $this->json($clientes);
    }

    /**
     * @Route("/buscarID", name="clientes_buscar_id", methods={"GET","POST"})
     */
    public function buscarID(Request $request, ClientesRepository $clientesRepository): Response
    {
        $c = $clientesRepository->findOneBy(['id' => $request->request->get('ID')]);

        $cliente = Array();
        
        if($c !== null){
            $cliente['razonSocial'] = $c->getRazonSocial();
            $cliente['nombre'] = $c->getNombre();
            $cliente['apellido'] = $c->getApellido();
            $cliente['dni'] = $c->getDni();
            $cliente['direccion'] = $c->getDireccion();
            $cliente['telefono'] = $c->getTelefono();
            $cliente['mail'] = $c->getMail();
        }else{
            $cliente['mensaje'] = 'No se encontraron usuarios';
        }
        
        return $this->json($cliente);
    }

    /**
     * @Route("/{id}", name="clientes_show", methods={"GET"})
     */
    public function show(Clientes $cliente): Response
    {
        return $this->render('clientes/show.html.twig', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="clientes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Clientes $cliente): Response
    {
        $form = $this->createForm(ClientesType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clientes_index');
        }

        return $this->render('clientes/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="clientes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Clientes $cliente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $cli = $entityManager->getRepository(Clientes::class)->find($cliente->getId());
            $cli->setEliminado(1);
            $entityManager->flush();

            /*
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cliente);
            $entityManager->flush();
            */
        }

        return $this->redirectToRoute('clientes_index');
    }
}
