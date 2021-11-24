<?php

namespace App\Controller;

use App\Entity\Ventas;
use App\Entity\Clientes;
use App\Entity\Almacenes;
use App\Entity\Productos;
use App\Entity\VentasProductos;
use App\Entity\VentasPagos;
use App\Entity\VentasPagosDetalle;
use App\Entity\VentasEnvios;
use App\Entity\ProductosAlmacenes;
use App\Entity\MediosPago;
use App\Entity\Caja;
use App\Entity\Cotizaciones;
use App\Entity\Usuarios;
use App\Entity\ReglasPreciosProductos;
use App\Form\VentasType;
use App\Repository\VentasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/sales")
 */
class VentasController extends AbstractController
{
    /**
     * @Route("/", name="ventas_index", methods={"GET","POST"})
     */
    public function index(Request $request, VentasRepository $ventasRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $filtrosAplicados = [
            'desde' => $request->request->get('filtroVentas')['desde'] ?? '',
            'hasta' => $request->request->get('filtroVentas')['hasta'] ?? '',
            'cliente' => $request->request->get('filtroVentas')['cliente'] ?? '',
            'vendedor' => $request->request->get('filtroVentas')['vendedor'] ?? '',
            'estado' => $request->request->get('filtroVentas')['estado'] ?? ''
        ];

        //$ventas = $ventasRepository->findAllHoy();
        
        $ventas = $ventasRepository->findAllFiltrado($filtrosAplicados);
        
        $ven = Array();
        $i = 0;
        if($ventas){
            foreach($ventas as $v){
                $vp = $entityManager->getRepository(VentasProductos::class)->findBy(['venta' => $v['id']]);
                $precioFinal = 0;
                foreach($vp as $p){
                    $precioFinal += $p->getPrecio() * $p->getCantidad();
                }

                $ven[$i]['id'] = $v['id'];
                $ven[$i]['fecha'] = $v['fecha'];
                $ven[$i]['estado'] = $v['estado'];
                $ven[$i]['cliente'] = ($v['cliente_id'] != null) ? $entityManager->getRepository(Clientes::class)->find($v['cliente_id']) : '';
                $ven[$i]['creador'] = $entityManager->getRepository(Usuarios::class)->find($v['creador_id']);
                $ven[$i]['precioFinal'] = $precioFinal;
                
                
                $i++;
            }
        }
        

        return $this->render('ventas/index.html.twig', [
            'ventas' => $ven,
            'filtrosAplicados' => $filtrosAplicados
        ]);
    }

    /**
     * @Route("/new", name="ventas_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('nueva_venta', $request->request->get('_token'))) {

            $venta = new Ventas();
            $venta->setFecha(new \DateTime($request->request->get('venta')['fecha']));
            $venta->setCliente($entityManager->getRepository(Clientes::class)->find($request->request->get('venta')['cliente']));
            $venta->setEstado($request->request->get('venta')['estado']);
            $venta->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('venta')['almacen']));
            $venta->setCreador($this->getUser());
            $venta->setPrecioFinal(0);
            $entityManager->persist($venta);
            $entityManager->flush();

            $mensajeProductos = $this->agregarProductosVenta($venta, $request->request->get('productoAsignado'), $request->request->get('stockReservado'));

            if($mensajeProductos['error'] != 0){
                return $this->redirectToRoute('ventas_edit', ['id' => $venta->getId(), 'mensajeError' => $mensajeProductos['mensaje']]);
            }else{
                
                if(isset($request->request->get('venta')['cotizacion'])){
                    // SI LA VENTA ES DE UNA COTIZACION LA CIERRO Y ASOCIO EL ID
                    $cotizacion = $entityManager->getRepository(Cotizaciones::class)->find($request->request->get('venta')['cotizacion']);
                    $cotizacion->setEstado('Finalizada');
                    $cotizacion->setVenta($venta);
                    $entityManager->flush();
                }

                if($request->request->get('guardar') == '1')
                    return $this->redirectToRoute('ventas_index');
                elseif($request->request->get('guardar') == '2')
                    return $this->redirectToRoute('ventas_pagos', ['id' => $venta->getId()]);
            }
        }

        return $this->render('ventas/new.html.twig', [
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => Array()
        ]);
    }

    /**
     * @Route("/{id}", name="ventas_show", methods={"GET"})
     */
    public function show(Ventas $venta): Response
    {
        return $this->render('ventas/show.html.twig', [
            'venta' => $venta,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ventas_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ventas $venta): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('editar_venta_'.$venta->getId(), $request->request->get('_token'))) {
            
            $venta->setFecha(new \DateTime($request->request->get('venta')['fecha']));
            $venta->setCliente($entityManager->getRepository(Clientes::class)->find($request->request->get('venta')['cliente']));
            $venta->setEstado($request->request->get('venta')['estado']);
            $venta->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('venta')['almacen']));
            $venta->setCreador($this->getUser());
            $venta->setPrecioFinal(0);
            $entityManager->flush();

            $mensajeProductos = $this->agregarProductosVenta($venta, $request->request->get('productoAsignado'));

            if($mensajeProductos['error'] != 0){
                return $this->redirectToRoute('ventas_edit', ['id' => $venta->getId(), 'mensajeError' => $mensajeProductos['mensaje']]);
            }else{
                if($request->request->get('guardar') == '1')
                    return $this->redirectToRoute('ventas_index');
                elseif($request->request->get('guardar') == '2')
                    return $this->redirectToRoute('ventas_pagos', ['id' => $venta->getId()]);
            }
        }
        
        $clienteCompleto = '';

        if($venta->getCliente() !== null)
            $clienteCompleto = $venta->getCliente()->getDni().' - '.$venta->getCliente()->getApellido().', '.$venta->getCliente()->getNombre().' - '.$venta->getCliente()->getMail();

        $productosAsignados = Array();
        $i = 0;
        $precioTotal = 0;
        foreach($entityManager->getRepository(VentasProductos::class)->findBy(['venta' => $venta->getId()]) as $p){

            $reglas = $this->getReglasPrecioProductos($p->getProducto()->getId());

            $productosAsignados[$i]['id'] = $p->getProducto()->getId();
            $productosAsignados[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignados[$i]['precio'] = $p->getPrecio();
            $productosAsignados[$i]['precioUnitario'] = $p->getProducto()->getPrecioFinal();
            $productosAsignados[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $venta->getAlmacen()])->getStock();
            $productosAsignados[$i]['cantidad'] = $p->getCantidad();
            $productosAsignados[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $productosAsignados[$i]['reglas'] = $reglas['reglasConcat'];
            $precioTotal += $p->getCantidad() * $p->getPrecio();
            $i++;
        }

        return $this->render('ventas/edit.html.twig', [
            'venta' => $venta,
            'clienteCompleto' => $clienteCompleto,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => $productosAsignados,
            'precioTotal' => $precioTotal
        ]);
    }

    /**
     * @Route("/{id}", name="ventas_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ventas $venta): Response
    {
        if ($this->isCsrfTokenValid('delete'.$venta->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($venta);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ventas_index');
    }

    // PRODUCTOS

    // NUEVOS
    /**
     * @Route("/productos", name="ventas_productos", methods={"GET","POST"})
     */
    public function ventasProductos(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $request->get('idProducto'), 'id_almacen' => $request->get('idAlmacen')]);
        
        $reglas = $this->getReglasPrecioProductos($request->get('idProducto'));

        $res['id'] = $prodAlm->getIdProducto()->getId();
        $res['titulo'] = $prodAlm->getIdProducto()->getTitulo();
        $res['precio'] = $prodAlm->getIdProducto()->getPrecioFinal();
        $res['stock'] = $prodAlm->getStock();
        $res['um'] = $prodAlm->getIdProducto()->getIdUnidadMedida()->getCorto();
        $res['reglaprecios'] = $reglas['reglasConcat'];

        return $this->json($res);
    }

    public function agregarProductosVenta(Ventas $venta, $prodCant, $prodReservado = 0){

        if($prodReservado == 0)
            $prodReservado = Array();

        $entityManager = $this->getDoctrine()->getManager();
        $ret['error'] = 0;
        $ret['mensaje'] = '';

        // REPONER STOCK EN CASO DE QUE SEA MODIFICACION PARA BORRAR LA TABLA ventas_productos
        $asignadosOld = $entityManager->getRepository(VentasProductos::class)->findBy(['venta' => $venta->getId()]);
        foreach($asignadosOld as $aO){
            $reponer = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $aO->getProducto(), 'id_almacen' => $venta->getAlmacen()]);
            $stockActual = $reponer->getStock() + $aO->getCantidad();
            $reponer->setStock($stockActual);
            $entityManager->remove($aO);
            $entityManager->flush();
        }

        // ASIGNO LOS PRODUCTOS DE VUELTA CON LA CANTIDAD QUE CORRESPONDE. SI NO HAY STOCK SUFICIENTE GUARDO EL RESTO Y MANDO MENSAJE
        $pc = Array();
        $i = 0;
        foreach(array_keys($prodCant) as $idProd){
            $producto = $entityManager->getRepository(Productos::class)->find($idProd);
            $prodActual = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $idProd, 'id_almacen' => $venta->getAlmacen()]);
            
            if($prodCant[$idProd] == '')
                $prodCant[$idProd] = 0;

            $prodStockNuevo = $prodActual->getStock() - $prodCant[$idProd];
            if($prodStockNuevo >= 0 or in_array($idProd, $prodReservado)){

                if(!in_array($idProd, $prodReservado))
                    $prodActual->setStock($prodStockNuevo);
                
                $reglas = $this->getReglasPrecioProductos($idProd, $prodCant[$idProd], $producto->getPrecioFinal());

                $vp = new VentasProductos();
                $vp->setProducto($producto);
                $vp->setVenta($venta);
                $vp->setCantidad($prodCant[$idProd]);
                $vp->setPrecio($reglas['precio']);
                $vp->setCosto($producto->getCosto());
                $entityManager->persist($vp);
                $entityManager->flush();

            }else{
                $ret['error'] = 1;
                $ret['mensaje'] .= "No hay suficiente stock del producto ".$producto->getTitulo()." (cantidad solicitada: ".$prodCant[$idProd].$producto->getIdUnidadMedida()->getCorto().", stock actual: ".$prodActual->getStock().$producto->getIdUnidadMedida()->getCorto().")<br/>";
            }
        }

        return $ret;
    }

    public function getReglasPrecioProductos($idProducto, $cantidad = 0, $precioUnitario = 0){
        $entityManager = $this->getDoctrine()->getManager();
        
        $reglasPrecios = $entityManager->getRepository(ReglasPreciosProductos::class)->findBy(['producto' => $idProducto],['cantidad' => 'ASC']);
        $data = Array(
            'precio' => $precioUnitario,
            'reglasConcat' => '',
            'reglas' => Array()
        );
        
        $i = 0;
        foreach($reglasPrecios as $rp){
            $data['reglasConcat'] .= $rp->getCantidad().'-'.$rp->getPrecio().'|';
            $data['reglas'][$i]['cantidad'] = $rp->getCantidad();
            $data['reglas'][$i]['precio'] = $rp->getPrecio();
            if($cantidad > 0){
                if($cantidad >= $rp->getCantidad()){
                    $data['precio'] = $rp->getPrecio();
                }
            }

            $i++;
        }

        return $data;
    }

    // ANTERIORES
    /**
     * @Route("/{id}/productos_OLD", name="ventas_productos_OLD", methods={"GET","POST"})
     */
    public function ventasProductos_OLD(Request $request, Ventas $venta): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(VentasProductos::class)->findBy(['venta' => $venta->getId()]);
        $productos = Array();
        $i = 0;
        foreach($prod as $p){
            $productos[$i]['idProducto'] = $p->getProducto()->getId();
            $productos[$i]['codigoBarras'] = $p->getProducto()->getCodigoBarras();
            $productos[$i]['categoria'] = $p->getProducto()->getCategoria()->getNombre();
            $productos[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productos[$i]['costo'] = $p->getCosto();
            $productos[$i]['precio'] = $p->getPrecio();
            $productos[$i]['cantidad'] = $p->getCantidad();
            $productos[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }
        return $this->render('ventas/productos.html.twig', [
            'venta' => $venta,
            'productos' => $productos
        ]);
    }

    /**
     * @Route("/asignar", name="ventas_productos_asignar", methods={"GET","POST"})
     */
    public function productosAsignar(Request $request): Response
    {
        $mensaje = '';
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $ven = $entityManager->getRepository(Ventas::class)->find($request->get('idVenta'));
        $venProd = $entityManager->getRepository(VentasProductos::class);
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $prod, 'id_almacen' => $ven->getAlmacen()]);
        $cant = $request->get('cantidad');

        $vp = $venProd->findOneBy(
            ['producto' => $prod, 'venta' => $ven]
        );

        if($vp === null) { 
            
            // Si el producto NO EXISTE lo cargo y resto el stock en productos_almacenes

            $vp = new VentasProductos();
            $vp->setProducto($prod);
            $vp->setVenta($ven);
            $vp->setCantidad($cant);
            $vp->setPrecio($prod->getPrecioFinal());
            $vp->setCosto($prod->getCosto());

            $stock = $prodAlm->getStock();
            $stockFinal = $stock - $cant;
            $prodAlm->setStock($stockFinal);
            $entityManager->persist($vp);
            $entityManager->flush();
        }else{

            // Si el producto estaba ASIGNADO actualizo cantidad y modifico el stock en productos_almacenes

            $dif = $vp->getCantidad() - $cant;
            $stock = $prodAlm->getStock();
            $stockFinal = $stock + $dif;
            $prodAlm->setStock($stockFinal);
            $vp->setCantidad($cant);
            $entityManager->flush();
        }

        $producto['id'] = $vp->getProducto()->getId();
        $producto['codigoBarras'] = $vp->getProducto()->getCodigoBarras();
        $producto['categoria'] = $vp->getProducto()->getCategoria()->getNombre();
        $producto['titulo'] = $vp->getProducto()->getTitulo();
        $producto['costo'] = $vp->getCosto();
        $producto['precio'] = $vp->getPrecio();
        $producto['cantidad'] = $vp->getCantidad();
        $producto['mensaje'] = $mensaje;

        return $this->json($producto);
    }

    /**
     * @Route("/eliminar", name="ventas_productos_eliminar", methods={"GET","POST"})
     */
    public function productosEliminar(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $ven = $entityManager->getRepository(Ventas::class)->find($request->get('idVenta'));
        $pv = $entityManager->getRepository(VentasProductos::class)->findOneBy(
            [
                'producto' => $prod,
                'venta' => $ven
            ]
        );
        
        // Devuelvo mercaderia al stock
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $prod, 'id_almacen' => $ven->getAlmacen()]);
        $stockFinal = $prodAlm->getStock() + $pv->getCantidad();
        $prodAlm->setStock($stockFinal);

        $entityManager->remove($pv);
        $entityManager->flush();
        return $this->json('OK!');
    }

    // DETALLES DE ENVIO

    /**
     * @Route("/{id}/envio", name="ventas_envio", methods={"GET","POST"})
     */
    public function envio(Request $request, Ventas $venta): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $envio = $entityManager->getRepository(VentasEnvios::class)->findOneBy(['venta' => $venta]);


        if ($this->isCsrfTokenValid('editar_envio', $request->request->get('_token'))) {
            $persist = false;
            if($envio === null){
                $envio = new VentasEnvios();
                $persist = true;
            }

            $envio->setVenta($venta);
            $envio->setCostoEnvio($request->request->get('envio')['costo']);
            $envio->setCostoEmbalaje($request->request->get('envio')['embalaje']);
            $envio->setCodigoSeguimiento($request->request->get('envio')['seguimiento']);

            if($persist)
                $entityManager->persist($envio);

            $venta->setEstado($request->request->get('envio')['estado']);
            
            $entityManager->flush();

            return $this->redirectToRoute('ventas_envio', ['id' => $venta->getId()]);
        }
        
        return $this->render('ventas/envios.html.twig', [
            'envio' => $envio,
            'venta' => $venta
        ]);
    }

    // PAGOS

    /**
     * @Route("/{id}/pagos", name="ventas_pagos", methods={"GET"})
     */
    public function pagosRender(Request $request, Ventas $venta): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // SI LA CAJA ESTA CERRADA MANDO A FRM CAJA
        $cajaAbiertaHoy = $entityManager->getRepository(Caja::class)->findCajaAbiertaHoy();
        if(count($cajaAbiertaHoy) == 0)
            return $this->redirectToRoute('caja_index');

        if($venta->getCliente() !== null)
            $clienteNYA = $venta->getCliente()->getNombre().' '.$venta->getCliente()->getApellido();

        $vp = $entityManager->getRepository(VentasProductos::class)->findBy(['venta' => $venta->getId()]);
        $precioProductos = 0;
        foreach($vp as $p){
            $precioProductos += $p->getPrecio() * $p->getCantidad();
        }

        $envio = $entityManager->getRepository(VentasEnvios::class)->findOneBy(['venta' => $venta]);
        $precioEnvio = ($envio !== null) ? $envio->getCostoEnvio() : 0;
        $precioEmbalaje = ($envio !== null) ? $envio->getCostoEmbalaje() : 0;

        $precioFinal = $precioProductos + $precioEnvio + $precioEmbalaje;
        $pendiente = $precioFinal;
        $pagos = $entityManager->getRepository(VentasPagos::class)->findBy(['venta' => $venta->getId()], ['fecha' => 'ASC']);
        foreach($pagos as $p){
            // SOLO CUENTO LOS PAGOS QUE NO SEAN POR CUENTA CORRIENTE
            if($p->getMedioPago()->getId() != 7)
                $pendiente -= $p->getMonto();
        }

        $bancos = Array(
            'BBVA Frances',
            'Citibank',
            'Comafi',
            'Credicoop Cooperativo',
            'Galicia',
            'Hipotecario',
            'HSBC',
            'Itaú',
            'Macro',
            'Nación',
            'Patagonia',
            'Piano',
            'Provincia',
            'Santander Rio',
            'Supervielle',
            'Otro...'
        );
        $entidadesTarjetas = Array(
            'American Express',
            'CABAL',
            'Master Card',
            'VISA',
            'Otro...'
        );

        $envio = $entityManager->getRepository(VentasEnvios::class)->findOneBy(['venta' => $venta]);

        if($envio === null){
            $envio['costoEmbalaje'] = 0;
            $envio['costoEnvio'] = 0;
        }

        $clienteCC = ($venta->getCliente()) ? $venta->getCliente()->getCuentaCorriente() : 0;

        return $this->render('ventas/pagos.html.twig', [
            'venta' => $venta,
            'clienteNYA' => $clienteNYA ?? '',
            'precioFinal' => $precioFinal,
            'precioProductos' => $precioProductos,
            'precioEnvio' => $precioEnvio,
            'precioEmbalaje' => $precioEmbalaje,
            'pendiente' => $pendiente,
            'pagos' => $pagos,
            'mediosPago' => $entityManager->getRepository(MediosPago::class)->findByClienteCC($clienteCC),
            'bancos' => $bancos,
            'entidadesTarjetas' => $entidadesTarjetas
        ]);
    }

    /**
     * @Route("/{id}/pagos", name="ventas_pagos_guardar", methods={"POST"})
     */
    public function pagosGuardar(Request $request, Ventas $venta): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('nuevo_pago_'.$venta->getId(), $request->request->get('_token'))) {
            // GUARDO EL PAGO
            $pago = new VentasPagos();
            $pago->setFecha(new \DateTime('now'));
            $pago->setComprobante(0);
            $pago->setNotaVenta($request->request->get('pago')['nota']);
            $pago->setMedioPago($entityManager->getRepository(MediosPago::class)->find($request->request->get('pago')['medio']));
            $pago->setInteres((float)$request->request->get('pago')['interes']);
            $pago->setMonto((float)$request->request->get('pago')['monto']);
            $pago->setObservaciones($request->request->get('pago')['observaciones']);
            $pago->setVenta($venta);
            $entityManager->persist($pago);
            $entityManager->flush();

            // GUARDO EL DETALLE DEL PAGO SEGUN EL MEDIO
            switch($request->request->get('pago')['medio']){
                case 2:
                    $numCheque = new VentasPagosDetalle();
                    $numCheque->setPago($pago);
                    $numCheque->setNombre('N° de cheque');
                    $numCheque->setValor($request->request->get('dp2')['numero']);
                    $acreditacionCheque = new VentasPagosDetalle();
                    $acreditacionCheque->setPago($pago);
                    $acreditacionCheque->setNombre('Fecha de acreditación');
                    $acreditacionCheque->setValor($request->request->get('dp2')['fecha_acreditacion']);
                    $bancoCheque = new VentasPagosDetalle();
                    $bancoCheque->setPago($pago);
                    $bancoCheque->setNombre('Banco');
                    $bancoCheque->setValor($request->request->get('dp2')['banco']);
                    
                    $entityManager->persist($numCheque);
                    $entityManager->persist($acreditacionCheque);
                    $entityManager->persist($bancoCheque);
                    
                    $entityManager->flush();
                break;
                case 3:
                    $codopMP = new VentasPagosDetalle();
                    $codopMP->setPago($pago);
                    $codopMP->setNombre('Código de operación');
                    $codopMP->setValor($request->request->get('dp3')['codop']);
                    $linkMP = new VentasPagosDetalle();
                    $linkMP->setPago($pago);
                    $linkMP->setNombre('Link de pago');
                    $linkMP->setValor($request->request->get('dp3')['link']);
                    
                    $entityManager->persist($codopMP);
                    $entityManager->persist($linkMP);
                    
                    $entityManager->flush();
                break;
                case 4:
                    $numTransferencia = new VentasPagosDetalle();
                    $numTransferencia->setPago($pago);
                    $numTransferencia->setNombre('N° de transferencia');
                    $numTransferencia->setValor($request->request->get('dp4')['numero']);
                    $bancoTransferencia = new VentasPagosDetalle();
                    $bancoTransferencia->setPago($pago);
                    $bancoTransferencia->setNombre('Banco');
                    $bancoTransferencia->setValor($request->request->get('dp4')['banco']);
                    
                    $entityManager->persist($numTransferencia);
                    $entityManager->persist($bancoTransferencia);
                    
                    $entityManager->flush();
                break;
                case 5:
                case 6:
                    $entidadTarjeta = new VentasPagosDetalle();
                    $entidadTarjeta->setPago($pago);
                    $entidadTarjeta->setNombre('Entidad');
                    $entidadTarjeta->setValor($request->request->get('dp56')['entidad']);
                    $bancoTarjeta = new VentasPagosDetalle();
                    $bancoTarjeta->setPago($pago);
                    $bancoTarjeta->setNombre('Banco');
                    $bancoTarjeta->setValor($request->request->get('dp56')['banco']);
                    
                    $entityManager->persist($bancoTarjeta);
                    $entityManager->persist($entidadTarjeta);
                    
                    $entityManager->flush();
                break;
                case 7:
                    $plazoCC = new VentasPagosDetalle();
                    $plazoCC->setPago($pago);
                    $plazoCC->setNombre('Plazo');
                    $plazoCC->setValor($request->request->get('dp7')['dias']);
                    
                    $entityManager->persist($plazoCC);
                    
                    $entityManager->flush();
                break;
            }

            // FINALIZO LA VENTA SI EL SALDO PENDIENTE ES 0
            $finalizar = $request->request->get('pago')['finalizar'] ?? 0;
            if($finalizar !== 0){
                $sumaPagos = $entityManager->getRepository(Ventas::class)->sumaPagos($venta->getId());
                $sumaPagos = $sumaPagos['total'];
                $sumaPrecioProductos = $entityManager->getRepository(Ventas::class)->sumaPrecioProductos($venta->getId());
                $envio = $entityManager->getRepository(VentasEnvios::class)->findOneBy(['venta' => $venta]);
                $precioEnvio = ($envio !== null) ? $envio->getCostoEnvio() : 0;
                $precioEmbalaje = ($envio !== null) ? $envio->getCostoEmbalaje() : 0;
                $precioFinal = $sumaPrecioProductos['total'] + $precioEnvio + $precioEmbalaje;

                if(intval($sumaPagos) == intval($precioFinal)){ // TIENE Q SER IGUAL A LA SUMA DE LOS PRODUCTOS, EMBALAJE Y ENVIOS!!!!
                    $venta->setEstado('Finalizada');
                    $entityManager->flush();
                }
            }
        }

        return $this->redirectToRoute('ventas_pagos', ['id' => $venta->getId()]);
    }

    // IMAGENES

    /**
     * @Route("/{id}/imagenes", name="ventas_imagenes", methods={"GET","POST"})
     */
    public function imagenes(Request $request, Ventas $venta): Response
    {
        $ID = $venta->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/ventas/'.$ID.'/';

        if ($this->isCsrfTokenValid('nueva_imagen_'.$ID, $request->request->get('_token'))) {

            $file = $request->files->get('imagen');
            $ahora = new \DateTime();
            $nombre = $ahora->format('U').'.'.$file->guessExtension();
            
            $file->move($ubicacion, $nombre);

            return $this->redirectToRoute('ventas_imagenes', ['id' => $ID]);
        }

        if(is_dir($ubicacion)){
            $finder = new Finder();
            $finder->files()->in($ubicacion);
        }else{
            $finder = Array();
        }
        $imagenes = Array();
        $i = 0;
        foreach ($finder as $file) {
            $imagenes[$i] = $file->getRelativePathname();
            $i++;
        }

        return $this->render('ventas/imagenes.html.twig', [
            'venta' => $venta,
            'imagenes' => $imagenes,
            'rutaImagenes' => 'images/ventas/'.$ID.'/'
        ]);
    }

    /**
     * @Route("/{id}/{imagen}/imagenes/borrar", name="ventas_imagenes_borrar", methods={"GET","POST"})
     */
    public function imagenesBorrar(Request $request, Ventas $venta, $imagen): Response
    {
        $ID = $venta->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/ventas/'.$ID.'/';
        $file = $ubicacion.$imagen;
  
        $filesystem = new Filesystem();
        if($filesystem->exists($file)){
            $filesystem->remove($file);
        }

        return $this->redirectToRoute('ventas_imagenes', ['id' => $ID]);
    }


    // EXCEL

    /**
     * @Route("/{id}/xls", name="ventas_xls", methods={"GET","POST"})
     */
    public function cotizacionesXls(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $idVenta = $request->attributes->get('id');
        $venta = $entityManager->getRepository(Ventas::class)->find($idVenta);
        $cliente = ($venta->getCliente()) ? $venta->getCliente()->getApellido().', '.$venta->getCliente()->getNombre() : 'Anónimo';
        $prod = $entityManager->getRepository(VentasProductos::class)->findBy(['venta' => $idVenta]);
        $productos = Array();
        $i = 0;
        $precioFinal = 0;
        foreach($prod as $p){
            $productos[$i]['codigoBarras'] = $p->getProducto()->getCodigoBarras();
            $productos[$i]['titulo'] = $p->getProducto()->getTitulo();
            $precioFinal += $p->getPrecio() * $p->getCantidad();
            $productos[$i]['cantidad'] = number_format($p->getCantidad(),0,',','.').' '.$p->getProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }
        $precioFinal = '$ '.number_format($precioFinal,2,',','.');

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Venta Jasa Sublimación');
        $sheet->getCell('A1')->setValue('Fecha de venta');
        $sheet->getCell('B1')->setValue($venta->getFecha()->format('d/m/Y'));
        $sheet->getCell('A3')->setValue('Cliente');
        $sheet->getCell('B3')->setValue($cliente);
        $sheet->getCell('A4')->setValue('Precio final');
        $sheet->getCell('B4')->setValue($precioFinal);
        $sheet->getCell('A5')->setValue('Detalle');
        $sheet->getCell('A6')->setValue('Código de barras');
        $sheet->getCell('B6')->setValue('Producto');
        $sheet->getCell('C6')->setValue('Cantidad');
        $sheet->fromArray($productos,null, 'A7', true);
        
        $writer = new Xlsx($spreadsheet);
        
        $nombre = $idVenta."-Venta_Jasa_Sublimacion.xlsx";
        $writer->save('ventas/'.$nombre);
        $rutaDescarga = 'http://'.$request->server->get('HTTP_HOST').'/ventas/'.$nombre;
        header('Location: '.$rutaDescarga);
        exit();
    }

    // PDF

    /**
     * @Route("/{id}/pdf", name="ventas_pdf", methods={"GET","POST"})
     */
    public function pdf(Ventas $venta): Response
    {
        
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('ventas/pdf.html.twig', [
            'venta' => $venta
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Venta -".$venta->getId()." - JASA SUBLIMACION.pdf", [
            "Attachment" => false
        ]);

        exit();

        return $this->render('ventas/pdf.html.twig', [
            'venta' => $venta
        ]);
    }

    /**
     * @Route("/{id}/pdfiva", name="ventas_pdf_iva", methods={"GET","POST"})
     */
    public function pdfiva(Ventas $venta): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('ventas/pdfiva.html.twig', [
            'venta' => $venta
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Venta -".$venta->getId()." - JASA SUBLIMACION.pdf", [
            "Attachment" => false
        ]);

        exit();

        
        return $this->render('ventas/pdfiva.html.twig', [
            'venta' => $venta
        ]);
    }

}
