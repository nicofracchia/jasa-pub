<?php

namespace App\Controller;

use App\Entity\Caja;
use App\Entity\MovimientosCaja;
use App\Entity\MovimientosCajaDiaria;
use App\Form\CajaType;
use App\Repository\CajaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/caja")
 */
class CajaController extends AbstractController
{
    /**
     * @Route("/", name="caja_index", methods={"GET"})
     */
    public function index(CajaRepository $cajaRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cajaAbiertaPendiente = $cajaRepository->findCajaAbiertaPendiente();
        $cajaAbiertaHoy = $cajaRepository->findCajaAbiertaHoy();
        $pestanias = Array();
        if(count($cajaAbiertaPendiente) > 0){ // CERRAR CAJA
            
            $montoEstimado = floatval($cajaAbiertaPendiente[0]['saldo_inicial']);
            $movimientos = $cajaRepository->findMontoEstimado($cajaAbiertaPendiente[0]['inicio']);
            foreach($movimientos as $m)
                $montoEstimado += floatval($m['m']);

            return $this->render('caja/cerrar.html.twig', [
                'caja' => $cajaAbiertaPendiente[0],
                'montoEstimado' => $montoEstimado
            ]);

        }elseif(count($cajaAbiertaHoy) == 0){ // ABRIR CAJA HOY
            
            return $this->render('caja/abrir.html.twig');

        }else{ // MOVIMIENTOS DE CAJA DIARIA

            $montoEstimado = floatval($cajaAbiertaHoy[0]['saldo_inicial']);
            $movimientos = $cajaRepository->findMontoEstimado($cajaAbiertaHoy[0]['inicio']);
            foreach($movimientos as $m)
                $montoEstimado += floatval($m['m']);
            
            return $this->render('caja/movimientos.html.twig', [
                'caja' => $cajaAbiertaHoy[0],
                'movimientos' => $cajaRepository->findMovimientosHoy($cajaAbiertaHoy[0]['inicio']),
                'montoEstimado' => $montoEstimado,
                'movimientosTipo' => $entityManager->getRepository(MovimientosCajaDiaria::class)->findAll()
            ]);

        }
    }

    /**
     * @Route("/abrir", name="caja_abrir", methods={"POST"})
     */
    public function abrir(Request $request): Response
    {
        if ($this->isCsrfTokenValid('iniciar_caja_diaria', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $caja = new Caja();
            $caja->setSaldoInicial($request->request->get('saldo'));
            $caja->setInicio(new \DateTime());
            $caja->setUsuarioApertura($this->getUser());
            $caja->setEstado(0);
            $entityManager->persist($caja);
            $entityManager->flush();
        }

        return $this->redirectToRoute('caja_index');
    }

    /**
      * @Route("/{id}/cerrar", name="caja_cerrar", methods={"GET","POST"})
      */
    public function cerrar(Request $request, CajaRepository $cajaRepository, Caja $caja): Response
    {
        if ($this->isCsrfTokenValid('cerrar_caja_diaria', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $caja->setSaldoFinal($request->request->get('saldo'));
            $caja->setSaldoEstimado($request->request->get('estimado'));
            $caja->setCierre(new \DateTime());
            $caja->setUsuarioCierre($this->getUser());
            $caja->setEstado(1);
            $entityManager->persist($caja);
            $entityManager->flush();
            return $this->redirectToRoute('caja_index');
        }

        $montoEstimado = floatval($caja->getSaldoInicial());
        $movimientos = $cajaRepository->findMontoEstimado($caja->getInicio()->format('Y-m-d H:i:s'));
        foreach($movimientos as $m)
            $montoEstimado += floatval($m['m']);

        return $this->render('caja/cerrar.html.twig', [
            'caja' => $caja,
            'montoEstimado' => $montoEstimado
        ]);
 
    }

     /**
       * @Route("/{id}/movimiento", name="caja_movimiento", methods={"POST"})
       */
      public function movimiento(Request $request, Caja $caja): Response
      {
          if ($this->isCsrfTokenValid('nuevo_movimiento', $request->request->get('_token'))) {
              $entityManager = $this->getDoctrine()->getManager();

              $tipoMovimiento = $entityManager->getRepository(MovimientosCajaDiaria::class)->find($request->request->get('movimiento')['movimiento']);
              
              $movimiento = new MovimientosCaja();
              $movimiento->setCaja($caja);
              $movimiento->setCreador($this->getUser());
              $movimiento->setFecha(new \DateTime());
              $movimiento->setMovimiento($tipoMovimiento->getMovimiento());
              $movimiento->setTipoMovimiento($tipoMovimiento->getTipo());
              $movimiento->setMonto($request->request->get('movimiento')['monto']);
              $movimiento->setObservaciones($request->request->get('movimiento')['observaciones']);
              $entityManager->persist($movimiento);
              $entityManager->flush();
          }
  
          return $this->redirectToRoute('caja_index');
      }

    /**
     * @Route("/movimientos", name="movimientos_modal", methods={"GET","POST"})
     */
    public function modalMovimientos(Request $request): Response
    {
        if ($this->isCsrfTokenValid('nuevo_movimiento', $request->request->get('token'))) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $movimiento = new MovimientosCajaDiaria();
            $movimiento->setMovimiento($request->request->get('movimiento'));
            $movimiento->setTipo((int)$request->request->get('tipo'));

            $entityManager->persist($movimiento);
            $entityManager->flush();
            
            $c = Array();
            $c['id'] = $movimiento->getId();
            $c['movimiento'] = $movimiento->getMovimiento();
            $c['tipo'] = $movimiento->getTipo();

            return $this->json($c);
        }
        return $this->render('generales/modalMovimientosCajaDiaria.html.twig');
    }
}
