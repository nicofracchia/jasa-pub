<?php

namespace App\Controller;

use App\Entity\Reparaciones;
use App\Entity\Productos;
use App\Entity\ProductosAlmacenes;
use App\Entity\Almacenes;
use App\Entity\Clientes;
use App\Entity\ReparacionesEstados;
use App\Entity\ReparacionesPagos;
use App\Entity\ReparacionesPagosDetalle;
use App\Entity\MediosPago;
use App\Entity\ReparacionesMarcas;
use App\Entity\ReparacionesModelos;
use App\Entity\ReparacionesTareas;
use App\Entity\ReparacionesProductos;
use App\Entity\Caja;
use App\Entity\ReglasPreciosProductos;
use App\Form\ReparacionesType;
use App\Repository\ReparacionesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/reparaciones")
 */
class ReparacionesController extends AbstractController
{
    /**
     * @Route("/", name="reparaciones_index", methods={"GET"})
     */
    public function index(ReparacionesRepository $reparacionesRepository): Response
    {
        return $this->render('reparaciones/index.html.twig', [
            'reparaciones' => $reparacionesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reparaciones_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('nuevo_ingreso', $request->request->get('_token'))) {
            $tintaC = ($request->request->get('reparacion')['tintac'] != '') ? $request->request->get('reparacion')['tintac'] : 0;
            $tintaM = ($request->request->get('reparacion')['tintam'] != '') ? $request->request->get('reparacion')['tintam'] : 0;
            $tintaY = ($request->request->get('reparacion')['tintay'] != '') ? $request->request->get('reparacion')['tintay'] : 0;
            $tintaCL = ($request->request->get('reparacion')['tintacl'] != '') ? $request->request->get('reparacion')['tintacl'] : 0;
            $tintaML = ($request->request->get('reparacion')['tintaml'] != '') ? $request->request->get('reparacion')['tintaml'] : 0;
            $tintaBk = ($request->request->get('reparacion')['tintabk'] != '') ? $request->request->get('reparacion')['tintabk'] : 0;

            $reparacion = new Reparaciones();
            $reparacion->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('reparacion')['almacen']));
            $reparacion->setCliente($entityManager->getRepository(Clientes::class)->find($request->request->get('reparacion')['cliente']));
            $reparacion->setReceptor($this->getUser());
            $reparacion->setRecepcion(new \DateTime('NOW'));
            $reparacion->setArticulo($request->request->get('reparacion')['articulo']);
            $reparacion->setMarca($request->request->get('reparacion')['marca']);
            $reparacion->setModelo($request->request->get('reparacion')['modelo']);
            $reparacion->setSerial($request->request->get('reparacion')['serial']);
            $reparacion->setTarea($request->request->get('reparacion')['tarea']);
            $reparacion->setReporte($request->request->get('reparacion')['reporte']);
            $reparacion->setTintaC($tintaC);
            $reparacion->setTintaM($tintaM);
            $reparacion->setTintaY($tintaY);
            $reparacion->setTintaCL($tintaCL);
            $reparacion->setTintaML($tintaML);
            $reparacion->setTintaBk($tintaBk);
            $reparacion->setPresupuestoInicial($request->request->get('reparacion')['estimado']);
            $reparacion->setSena($request->request->get('reparacion')['sena']);
            $reparacion->setEstado('Ingresado');
            $entityManager->persist($reparacion);
            $entityManager->flush();

            if($request->request->get('guardar') == '1')
                return $this->redirectToRoute('reparaciones_index');
            elseif($request->request->get('guardar') == '2')
                return $this->redirectToRoute('reparaciones_imagenes', ['id' => $reparacion->getId()]);
            elseif($request->request->get('guardar') == '3')
                return $this->redirectToRoute('reparaciones_pagos', ['id' => $reparacion->getId()]);
            elseif($request->request->get('guardar') == '4')
                return $this->redirectToRoute('reparaciones_detalle', ['id' => $reparacion->getId()]);
        }

        return $this->render('reparaciones/new.html.twig', [
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'marcas' => $entityManager->getRepository(ReparacionesMarcas::class)->findAll(),
            'modelos' => $entityManager->getRepository(ReparacionesModelos::class)->findAll(),
            'tareas' => $entityManager->getRepository(ReparacionesTareas::class)->findAll()
        ]);
    }

    /**
     * @Route("/{id}/ingreso", name="reparaciones_ingreso", methods={"GET","POST"})
     */
    public function ingreso(Request $request, Reparaciones $reparacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('editar_ingreso_'.$reparacion->getId(), $request->request->get('_token'))) {
            $reparacion->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('reparacion')['almacen']));
            $reparacion->setCliente($entityManager->getRepository(Clientes::class)->find($request->request->get('reparacion')['cliente']));
            $reparacion->setReceptor($this->getUser());
            $reparacion->setRecepcion(new \DateTime('NOW'));
            $reparacion->setArticulo($request->request->get('reparacion')['articulo']);
            $reparacion->setMarca($request->request->get('reparacion')['marca']);
            $reparacion->setModelo($request->request->get('reparacion')['modelo']);
            $reparacion->setSerial($request->request->get('reparacion')['serial']);
            $reparacion->setTarea($request->request->get('reparacion')['tarea']);
            $reparacion->setReporte($request->request->get('reparacion')['reporte']);
            $reparacion->setTintaC($request->request->get('reparacion')['tintac']);
            $reparacion->setTintaM($request->request->get('reparacion')['tintam']);
            $reparacion->setTintaY($request->request->get('reparacion')['tintay']);
            $reparacion->setTintaCL($request->request->get('reparacion')['tintacl']);
            $reparacion->setTintaML($request->request->get('reparacion')['tintaml']);
            $reparacion->setTintaBk($request->request->get('reparacion')['tintabk']);
            $reparacion->setPresupuestoInicial($request->request->get('reparacion')['estimado']);
            $reparacion->setSena($request->request->get('reparacion')['sena']);
            $reparacion->setEstado('Ingresado');
            $entityManager->flush();

            if($request->request->get('guardar') == '1')
                return $this->redirectToRoute('reparaciones_index');
            elseif($request->request->get('guardar') == '2')
                return $this->redirectToRoute('reparaciones_imagenes', ['id' => $reparacion->getId()]);
            elseif($request->request->get('guardar') == '3')
                return $this->redirectToRoute('reparaciones_pagos', ['id' => $reparacion->getId()]);
            elseif($request->request->get('guardar') == '4')
                return $this->redirectToRoute('reparaciones_detalle', ['id' => $reparacion->getId()]);
        }

        return $this->render('reparaciones/ingreso.html.twig', [
            'reparacion' => $reparacion,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'marcas' => $entityManager->getRepository(ReparacionesMarcas::class)->findAll(),
            'modelos' => $entityManager->getRepository(ReparacionesModelos::class)->findAll(),
            'tareas' => $entityManager->getRepository(ReparacionesTareas::class)->findAll()
        ]);
    }

    /**
     * @Route("/{id}/mesa", name="reparaciones_mesa", methods={"GET","POST"})
     */
    public function mesa(Request $request, Reparaciones $reparacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $mensajeError = ($request->get('mensajeError')) ? $request->get('mensajeError') : '';

        if ($this->isCsrfTokenValid('mesa_trabajo_'.$reparacion->getId(), $request->request->get('_token'))) {
            $reparacion->setDiagnostico($request->request->get('reparacion')['diagnostico']);
            $reparacion->setPresupuestoFinal($request->request->get('reparacion')['precio']);
            $reparacion->setEstado($request->request->get('reparacion')['estado']);
            $reparacion->setObservaciones($request->request->get('reparacion')['observaciones']);
            $entityManager->flush();
            
            $estados = new ReparacionesEstados();
            $estados->setReparacion($reparacion);
            $estados->setFecha(new \DateTime('NOW'));
            $estados->setEstado($request->request->get('reparacion')['estado']);
            $entityManager->persist($estados);
            $entityManager->flush();

            $mensajeProductos = $this->agregarProductosReparacion($reparacion, $request->request->get('productoAsignado'));

            if($mensajeProductos['error'] != 0){
                return $this->redirectToRoute('reparaciones_mesa', ['id' => $reparacion->getId(), 'mensajeError' => $mensajeProductos['mensaje']]);
            }else{
                if($request->request->get('guardar') == '1')
                    return $this->redirectToRoute('reparaciones_index');
                elseif($request->request->get('guardar') == '2')
                    return $this->redirectToRoute('reparaciones_imagenes', ['id' => $reparacion->getId()]);
            }
        }

        $productosAsignados = Array();
        $i = 0;
        $precioTotal = 0;
        foreach($entityManager->getRepository(ReparacionesProductos::class)->findBy(['reparacion' => $reparacion->getId()]) as $p){
            
            $reglas = $this->getReglasPrecioProductos($p->getProducto()->getId());

            $productosAsignados[$i]['id'] = $p->getProducto()->getId();
            $productosAsignados[$i]['titulo'] = $p->getProducto()->getTitulo();
            $productosAsignados[$i]['precio'] = $p->getPrecio();
            $productosAsignados[$i]['precioUnitario'] = $p->getProducto()->getPrecioFinal();
            $productosAsignados[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getProducto()->getId(), 'id_almacen' => $reparacion->getAlmacen()])->getStock();
            $productosAsignados[$i]['cantidad'] = $p->getCantidad();
            $productosAsignados[$i]['unidad'] = $p->getProducto()->getIdUnidadMedida()->getCorto();
            $productosAsignados[$i]['reglas'] = $reglas['reglasConcat'];
            $precioTotal += $p->getCantidad() * $p->getPrecio();
            $i++;
        }

        return $this->render('reparaciones/mesa.html.twig', [
            'reparacion' => $reparacion,
            'mensajeError' => $mensajeError,
            'productosAsignados' => $productosAsignados,
            'precioTotal' => $precioTotal
        ]);
    }

    public function agregarProductosReparacion(Reparaciones $reparacion, $prodCant, $prodReservado = 0){

        if($prodReservado == 0)
            $prodReservado = Array();

        $entityManager = $this->getDoctrine()->getManager();
        $ret['error'] = 0;
        $ret['mensaje'] = Array();

        // REPONER STOCK EN CASO DE QUE SEA MODIFICACION PARA BORRAR LA TABLA reparaciones_productos
        $asignadosOld = $entityManager->getRepository(ReparacionesProductos::class)->findBy(['reparacion' => $reparacion->getId()]);
        foreach($asignadosOld as $aO){
            $reponer = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $aO->getProducto(), 'id_almacen' => $reparacion->getAlmacen()]);
            $stockActual = $reponer->getStock() + $aO->getCantidad();
            $reponer->setStock($stockActual);
            $entityManager->remove($aO);
            $entityManager->flush();
        }

        // ASIGNO LOS PRODUCTOS DE VUELTA CON LA CANTIDAD QUE CORRESPONDE. SI NO HAY STOCK SUFICIENTE GUARDO EL RESTO Y MANDO MENSAJE
        $pc = Array();
        $i = 0;
        $prodCant = ($prodCant !== null) ? $prodCant : Array();
        
        foreach(array_keys($prodCant) as $idProd){
            $producto = $entityManager->getRepository(Productos::class)->find($idProd);
            $prodActual = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $idProd, 'id_almacen' => $reparacion->getAlmacen()]);
            
            if($prodCant[$idProd] == '')
                $prodCant[$idProd] = 0;

            $prodStockNuevo = $prodActual->getStock() - $prodCant[$idProd];
            if($prodStockNuevo >= 0 or in_array($idProd, $prodReservado)){

                if(!in_array($idProd, $prodReservado))
                    $prodActual->setStock($prodStockNuevo);
                
                $reglas = $this->getReglasPrecioProductos($idProd, $prodCant[$idProd], $producto->getPrecioFinal());
                
                $rp = new ReparacionesProductos();
                $rp->setProducto($producto);
                $rp->setReparacion($reparacion);
                $rp->setCantidad($prodCant[$idProd]);
                $rp->setPrecio($reglas['precio']);
                $rp->setCosto($producto->getCosto());
                $entityManager->persist($rp);
                $entityManager->flush();

            }else{
                $ret['error'] = 1;
                $ret['mensaje'][count($ret['mensaje'])] = "No hay suficiente stock del material ".$producto->getTitulo()." (cantidad solicitada: ".$prodCant[$idProd].$producto->getIdUnidadMedida()->getCorto().", stock actual: ".$prodActual->getStock().$producto->getIdUnidadMedida()->getCorto().")";
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

    /**
     * @Route("/{id}/imagenes", name="reparaciones_imagenes", methods={"GET","POST"})
     */
    public function imagenes(Request $request, Reparaciones $reparacion): Response
    {
        $ID = $reparacion->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/reparaciones/'.$ID.'/';

        if ($this->isCsrfTokenValid('nueva_imagen_'.$ID, $request->request->get('_token'))) {

            $file = $request->files->get('imagen');
            $ahora = new \DateTime();
            $nombre = $ahora->format('U').'.'.$file->guessExtension();
            
            $file->move($ubicacion, $nombre);

            return $this->redirectToRoute('reparaciones_imagenes', ['id' => $ID]);
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

        return $this->render('reparaciones/imagenes.html.twig', [
            'reparacion' => $reparacion,
            'imagenes' => $imagenes,
            'rutaImagenes' => 'images/reparaciones/'.$ID.'/'
        ]);
    }

    /**
     * @Route("/{id}/{imagen}/imagenes/borrar", name="reparaciones_imagenes_borrar", methods={"GET","POST"})
     */
    public function imagenesBorrar(Request $request, Reparaciones $reparacion, $imagen): Response
    {
        $ID = $reparacion->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/reparaciones/'.$ID.'/';
        $file = $ubicacion.$imagen;
  
        $filesystem = new Filesystem();
        if($filesystem->exists($file)){
            $filesystem->remove($file);
        }

        return $this->redirectToRoute('reparaciones_imagenes', ['id' => $ID]);
    }

    /**
     * @Route("/{id}/detalle", name="reparaciones_detalle", methods={"GET","POST"})
     */
    public function detalle(Request $request, Reparaciones $reparacion): Response
    {
        return $this->render('reparaciones/detalle.html.twig', [
            'reparacion' => $reparacion
        ]);
    }

    /**
     * @Route("/{id}/pdf", name="reparaciones_pdf", methods={"GET","POST"})
     */
    public function pdf(Reparaciones $reparacion): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reparaciones/pdf.html.twig', [
            'logo' => $this->getParameter('kernel.project_dir').'/public/images/logo.png',
            'reparacion' => $reparacion
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'landscape'
        $dompdf->setPaper('A5', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Orden de reparación LDZ-".$reparacion->getId()." - JASA SUBLIMACION.pdf", [
            "Attachment" => false
        ]);

        exit();
        
        return $this->render('reparaciones/pdf.html.twig', [
            'logo' => $this->getParameter('kernel.project_dir').'/public/images/logo.png',
            'reparacion' => $reparacion
        ]);

        
    }

    /**
     * @Route("/{id}/pagos", name="reparaciones_pagos", methods={"GET"})
     */
    public function pagosRender(Request $request, Reparaciones $reparacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // SI LA CAJA ESTA CERRADA MANDO A FRM CAJA
        $cajaAbiertaHoy = $entityManager->getRepository(Caja::class)->findCajaAbiertaHoy();
        if(count($cajaAbiertaHoy) == 0)
            return $this->redirectToRoute('caja_index');

        if($reparacion->getCliente() !== null)
            $clienteNYA = $reparacion->getCliente()->getNombre().' '.$reparacion->getCliente()->getApellido();

        $precioFinal = $reparacion->getPresupuestoFinal();
        $pendiente = $precioFinal;
        $pagos = $entityManager->getRepository(ReparacionesPagos::class)->findBy(['reparacion' => $reparacion->getId()], ['fecha' => 'ASC']);
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

        $clienteCC = ($reparacion->getCliente()) ? $reparacion->getCliente()->getCuentaCorriente() : 0;

        return $this->render('reparaciones/pagos.html.twig', [
            'reparacion' => $reparacion,
            'clienteNYA' => $clienteNYA ?? '',
            'precioFinal' => $precioFinal,
            'mediosPago' => $entityManager->getRepository(MediosPago::class)->findByClienteCC($clienteCC),
            'pagos' => $pagos,
            'pendiente' => $pendiente,
            'bancos' => $bancos,
            'entidadesTarjetas' => $entidadesTarjetas
        ]);
    }

    /**
     * @Route("/{id}/pagos", name="reparaciones_pagos_guardar", methods={"POST"})
     */
    public function pagosGuardar(Request $request, Reparaciones $reparacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('nuevo_pago_'.$reparacion->getId(), $request->request->get('_token'))) {
            // GUARDO EL PAGO
            $pago = new ReparacionesPagos();
            $pago->setFecha(new \DateTime('now'));
            $pago->setComprobante(0);
            $pago->setNotaVenta($request->request->get('pago')['nota']);
            $pago->setMedioPago($entityManager->getRepository(MediosPago::class)->find($request->request->get('pago')['medio']));
            $pago->setInteres((float)$request->request->get('pago')['interes']);
            $pago->setMonto((float)$request->request->get('pago')['monto']);
            $pago->setObservaciones($request->request->get('pago')['observaciones']);
            $pago->setReparacion($reparacion);
            $entityManager->persist($pago);
            $entityManager->flush();

            // GUARDO EL DETALLE DEL PAGO SEGUN EL MEDIO
            switch($request->request->get('pago')['medio']){
                case 2:
                    $numCheque = new ReparacionesPagosDetalle();
                    $numCheque->setPago($pago);
                    $numCheque->setNombre('N° de cheque');
                    $numCheque->setValor($request->request->get('dp2')['numero']);
                    $acreditacionCheque = new ReparacionesPagosDetalle();
                    $acreditacionCheque->setPago($pago);
                    $acreditacionCheque->setNombre('Fecha de acreditación');
                    $acreditacionCheque->setValor($request->request->get('dp2')['fecha_acreditacion']);
                    $bancoCheque = new ReparacionesPagosDetalle();
                    $bancoCheque->setPago($pago);
                    $bancoCheque->setNombre('Banco');
                    $bancoCheque->setValor($request->request->get('dp2')['banco']);
                    
                    $entityManager->persist($numCheque);
                    $entityManager->persist($acreditacionCheque);
                    $entityManager->persist($bancoCheque);
                    
                    $entityManager->flush();
                break;
                case 3:
                    $codopMP = new ReparacionesPagosDetalle();
                    $codopMP->setPago($pago);
                    $codopMP->setNombre('Código de operación');
                    $codopMP->setValor($request->request->get('dp3')['codop']);
                    $linkMP = new ReparacionesPagosDetalle();
                    $linkMP->setPago($pago);
                    $linkMP->setNombre('Link de pago');
                    $linkMP->setValor($request->request->get('dp3')['link']);
                    
                    $entityManager->persist($codopMP);
                    $entityManager->persist($linkMP);
                    
                    $entityManager->flush();
                break;
                case 4:
                    $numTransferencia = new ReparacionesPagosDetalle();
                    $numTransferencia->setPago($pago);
                    $numTransferencia->setNombre('N° de transferencia');
                    $numTransferencia->setValor($request->request->get('dp4')['numero']);
                    $bancoTransferencia = new ReparacionesPagosDetalle();
                    $bancoTransferencia->setPago($pago);
                    $bancoTransferencia->setNombre('Banco');
                    $bancoTransferencia->setValor($request->request->get('dp4')['banco']);
                    
                    $entityManager->persist($numTransferencia);
                    $entityManager->persist($bancoTransferencia);
                    
                    $entityManager->flush();
                break;
                case 5:
                case 6:
                    $entidadTarjeta = new ReparacionesPagosDetalle();
                    $entidadTarjeta->setPago($pago);
                    $entidadTarjeta->setNombre('Entidad');
                    $entidadTarjeta->setValor($request->request->get('dp56')['entidad']);
                    $bancoTarjeta = new ReparacionesPagosDetalle();
                    $bancoTarjeta->setPago($pago);
                    $bancoTarjeta->setNombre('Banco');
                    $bancoTarjeta->setValor($request->request->get('dp56')['banco']);
                    
                    $entityManager->persist($bancoTarjeta);
                    $entityManager->persist($entidadTarjeta);
                    
                    $entityManager->flush();
                break;
                case 7:
                    $plazoCC = new ReparacionesPagosDetalle();
                    $plazoCC->setPago($pago);
                    $plazoCC->setNombre('Plazo');
                    $plazoCC->setValor($request->request->get('dp7')['dias']);
                    
                    $entityManager->persist($plazoCC);
                    
                    $entityManager->flush();
                break;
            }
        }

        return $this->redirectToRoute('reparaciones_pagos', ['id' => $reparacion->getId()]);
    }

    /**
     * @Route("/marcas", name="marcas_modal", methods={"GET","POST"})
     */
    public function modalMarcas(Request $request): Response
    {
        if ($this->isCsrfTokenValid('nueva_marca', $request->request->get('token'))) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $marca = new ReparacionesMarcas();
            $marca->setMarca($request->request->get('marca'));
            $marca->setFecha(new \DateTime('NOW'));

            $entityManager->persist($marca);
            $entityManager->flush();
            
            $c = Array();
            $c['id'] = $marca->getId();
            $c['marca'] = $marca->getMarca();
            $c['fecha'] = $marca->getFecha();

            return $this->json($c);
        }
        return $this->render('generales/modalMarca.html.twig');
    }

    /**
     * @Route("/modelos", name="modelos_modal", methods={"GET","POST"})
     */
    public function modalModelos(Request $request): Response
    {
        if ($this->isCsrfTokenValid('nuevo_modelo', $request->request->get('token'))) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $modelo = new ReparacionesModelos();
            $modelo->setModelo($request->request->get('modelo'));
            $modelo->setFecha(new \DateTime('NOW'));

            $entityManager->persist($modelo);
            $entityManager->flush();
            
            $c = Array();
            $c['id'] = $modelo->getId();
            $c['modelo'] = $modelo->getModelo();
            $c['fecha'] = $modelo->getFecha();

            return $this->json($c);
        }
        return $this->render('generales/modalModelo.html.twig');
    }

    /**
     * @Route("/tareas", name="tareas_modal", methods={"GET","POST"})
     */
    public function modalTareas(Request $request): Response
    {
        if ($this->isCsrfTokenValid('nueva_tarea', $request->request->get('token'))) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $tarea = new ReparacionesTareas();
            $tarea->setTarea($request->request->get('tarea'));
            $tarea->setFecha(new \DateTime('NOW'));

            $entityManager->persist($tarea);
            $entityManager->flush();
            
            $c = Array();
            $c['id'] = $tarea->getId();
            $c['tarea'] = $tarea->getTarea();
            $c['fecha'] = $tarea->getFecha();

            return $this->json($c);
        }
        return $this->render('generales/modalTarea.html.twig');
    }
}
