<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ReportesRepository;
use App\Repository\UsuariosRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/reportes")
 */
class ReportesController extends AbstractController
{
    /**
     * @Route("/", name="reportes_index")
     */
    public function index(Request $request, UsuariosRepository $usuariosRep): Response
    {
        if ($this->isCsrfTokenValid('ver_reportes', $request->request->get('_token'))) {

            $desde = $request->request->get('reporte')['desde'];
            $hasta = $request->request->get('reporte')['hasta'];
            $cliente = $request->request->get('reporte')['cliente'];
            $medioPago = $request->request->get('reporte')['medio_pago'];
            $producto = $request->request->get('reporte')['producto'] ?? '';
            $usuario = $request->request->get('reporte')['usuario'] ?? '';
            
            switch($request->request->get('reporte')['reporte']){
                case 1:
                    return $this->redirectToRoute('reportes_caja_diaria', ['desde' => $desde, 'hasta' => $hasta]);
                    break;
                case 2:
                    // MEDIOS PAGO TOTALES
                    return $this->redirectToRoute('reportes_medio_pago', ['desde' => $desde, 'hasta' => $hasta, 'medio' => $medioPago]);
                    break;
                case 3:
                    return $this->redirectToRoute('reportes_usuarios', ['desde' => $desde, 'hasta' => $hasta]);
                    break;
                case 4:
                    // CLIENTES TOTALES
                    return $this->redirectToRoute('reportes_clientes', ['desde' => $desde, 'hasta' => $hasta, 'cliente' => $cliente]);
                    break;
                case 5:
                    return $this->redirectToRoute('reportes_productos', ['desde' => $desde, 'hasta' => $hasta]);
                    break;
                case 6:
                    return $this->redirectToRoute('reportes_movimientos', ['desde' => $desde, 'hasta' => $hasta]);
                    break;
                case 7:
                    // CLIENTES DETALLE
                    return $this->redirectToRoute('reportes_clientes_detalle', ['desde' => $desde, 'hasta' => $hasta, 'cliente' => $cliente]);
                    break;
                case 8:
                    // MEDIOS PAGO DETALLADO
                    return $this->redirectToRoute('reportes_medio_pago_detalle', ['desde' => $desde, 'hasta' => $hasta, 'medio' => $medioPago]);
                    break;
                case 9:
                    // VENTAS POR PRODUCTOS Y USUARIOS DETALLADO
                    return $this->redirectToRoute('reportes_ventas_productos_usuarios', ['desde' => $desde, 'hasta' => $hasta, 'producto' => $producto, 'usuario' => $usuario]);
                    break;

            }
        }

        $usuarios = $usuariosRep->findAll();

        return $this->render('reportes/index.html.twig', [
            'controller_name' => 'ReportesController',
            'usuarios' => $usuarios
        ]);
    }

    /**
     * @Route("/medio_pago", name="reportes_medio_pago")
     */
    public function mediosDePago(ReportesRepository $repo, Request $request): Response
    {
        $tabla = $repo->getMediosPago($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59", $request->get('medio'));
        $dataGrafico = "";
        $total = 0;
        foreach($tabla as $t){
            $total += $t['total'];
        }
        foreach($tabla as $t){
            $porcentaje = number_format(100 * $t['total'] / $total, 0, ',', '.');
            $dataGrafico .= "{";
            $dataGrafico .= "name: '".$t['nombre']." ( ".$porcentaje."% )',";
            $dataGrafico .= "data: [".number_format($t['total'], 0, '', '')."]";
            $dataGrafico .= "},";
        }
        $dataGrafico = substr($dataGrafico,0,-1);

        return $this->render('reportes/medioPago.html.twig', [
            'tabla' => $tabla,
            'dataGrafico' => $dataGrafico
        ]);
    }

     /**
     * @Route("/medio_pago_detalle", name="reportes_medio_pago_detalle")
     */
    public function mediosDePagoDetalle(ReportesRepository $repo, Request $request): Response
    {
        $tabla = $repo->getMediosPagoDetalle($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59", $request->get('medio'));
        
        return $this->render('reportes/medioPagoDetalle.html.twig', [
            'tabla' => $tabla
        ]);
    }

    /**
     * @Route("/usuarios", name="reportes_usuarios")
     */
    public function Usuarios(ReportesRepository $repo, Request $request): Response
    {
        $tabla = $repo->getUsuarios($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59");
        $dataGrafico = "";
        $total = 0;
        foreach($tabla as $t){
            $total += $t['total'];
        }
        foreach($tabla as $t){
            $porcentaje = number_format(100 * $t['total'] / $total, 0, ',', '.');
            $dataGrafico .= "{";
            $dataGrafico .= "name: '".$t['usuario']." ( ".$porcentaje."% )',";
            $dataGrafico .= "data: [".number_format($t['total'], 0, '', '')."]";
            $dataGrafico .= "},";
        }
        $dataGrafico = substr($dataGrafico,0,-1);

        return $this->render('reportes/usuarios.html.twig', [
            'tabla' => $tabla,
            'dataGrafico' => $dataGrafico
        ]);
    }

    /**
     * @Route("/clientes", name="reportes_clientes")
     */
    public function Clientes(ReportesRepository $repo, Request $request): Response
    {
        $tabla = $repo->getClientes($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59", $request->get('cliente'));
        $dataGrafico = "";
        $total = 0;
        foreach($tabla as $t){
            $total += $t['total'];
        }
        foreach($tabla as $t){
            $porcentaje = number_format(100 * $t['total'] / $total, 0, ',', '.');
            $dataGrafico .= "{";
            $dataGrafico .= "name: '".$t['cliente']." ( ".$porcentaje."% )',";
            $dataGrafico .= "data: [".number_format($t['total'], 0, '', '')."]";
            $dataGrafico .= "},";
        }
        $dataGrafico = substr($dataGrafico,0,-1);

        return $this->render('reportes/clientes.html.twig', [
            'tabla' => $tabla,
            'dataGrafico' => $dataGrafico
        ]);
    }

    /**
     * @Route("/clientes/detalle", name="reportes_clientes_detalle")
     */
    public function ClientesDetalle(ReportesRepository $repo, Request $request): Response
    {
        $tabla = $repo->getClientesDetalle($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59", $request->get('cliente'));

        return $this->render('reportes/clientesDetalle.html.twig', [
            'tabla' => $tabla
        ]);
    }

    /**
     * @Route("/productos", name="reportes_productos")
     */
    public function Productos(ReportesRepository $repo, Request $request): Response
    {
        $tabla = $repo->getProductos($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59");
        $dataGrafico = "";
        $categoriasGrafico = "";
        $cantidad = "";
        $costo = "";
        $precio = "";
        
        foreach($tabla as $t){
            $categoriasGrafico .= "'".$t['titulo']."',";
            $cantidad .= number_format($t['cantidad'], 0, '', '').",";
            $costo .= number_format($t['costo'], 0, '', '').",";
            $precio .= number_format($t['precio'], 0, '', '').",";
        }
        
        $dataGrafico .= "{";
        $dataGrafico .= "name: 'Cantidad',";
        $dataGrafico .= "data: [".substr($cantidad,0,-1)."]";
        $dataGrafico .= "},";
        $dataGrafico .= "{";
        $dataGrafico .= "name: 'Costo',";
        $dataGrafico .= "data: [".substr($costo,0,-1)."]";
        $dataGrafico .= "},";
        $dataGrafico .= "{";
        $dataGrafico .= "name: 'Precio',";
        $dataGrafico .= "data: [".substr($precio,0,-1)."]";
        $dataGrafico .= "}";

        $categoriasGrafico = substr($categoriasGrafico,0,-1);

        return $this->render('reportes/productos.html.twig', [
            'tabla' => $tabla,
            'dataGrafico' => $dataGrafico,
            'categoriasGrafico' => $categoriasGrafico
        ]);
    }

    /**
     * @Route("/movimientos", name="reportes_movimientos")
     */
    public function Movimientos(ReportesRepository $repo, Request $request): Response
    {
        return $this->render('reportes/movimientos.html.twig', [
            'tabla' => $repo->getMovimientos($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59")
        ]);
    }

    /**
     * @Route("/caja", name="reportes_caja_diaria")
     */
    public function CajaDiaria(ReportesRepository $repo, Request $request): Response
    {
        return $this->render('reportes/cajaDiaria.html.twig', [
            'tabla' => $repo->getCajaDiaria($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59")
        ]);
    }

    /**
     * @Route("/ventas/productos/usuarios", name="reportes_ventas_productos_usuarios")
     */
    public function VentasProductosUsuarios(ReportesRepository $repo, Request $request): Response
    {
        return $this->render('reportes/ventasProductosUsuarios.html.twig', [
            'tabla' => $repo->getVentasProductosUsuarios($request->get('desde')." 00:00:00", $request->get('hasta')." 23:59:59", $request->get('producto'), $request->get('usuario'))
        ]);
    }
    
}
