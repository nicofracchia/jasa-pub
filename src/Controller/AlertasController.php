<?php

namespace App\Controller;

use App\Entity\Productos;
use App\Entity\Cotizaciones;
use App\Entity\Caja;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AlertasController extends AbstractController
{
    /**
     * @Route("/alertas", name="alertas")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $caja = $entityManager->getRepository(caja::class)->findUltimoCierre();
        $productos = $entityManager->getRepository(Productos::class)->alertas();
        $cotizaciones = $entityManager->getRepository(Cotizaciones::class)->alertas();

        return $this->render('alertas/index.html.twig', [
            'caja' => $caja,
            'productos' => $productos,
            'cotizaciones' => $cotizaciones,
        ]);
    }
}
