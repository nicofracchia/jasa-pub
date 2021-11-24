<?php

namespace App\Controller;

use App\Entity\Cotizaciones;
use App\Entity\Clientes;
use App\Entity\Almacenes;
use App\Entity\Productos;
use App\Entity\CotizacionesProductos;
use App\Entity\ProductosAlmacenes;
use App\Entity\Caja;
use App\Entity\ReglasPreciosProductos;
use App\Form\CotizacionesType;
use App\Repository\CotizacionesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpKernel\KernelInterface;


/**
 * @Route("/cotizador")
 */
class CotizacionesController extends AbstractController
{
    /**
     * @Route("/", name="cotizaciones_index", methods={"GET"})
     */
    public function index(CotizacionesRepository $cotizacionesRepository): Response
    {
        $cot = $cotizacionesRepository->findAll();
        $cotizaciones = Array();
        $i = 0;
        foreach($cot as $c){
            $entityManager = $this->getDoctrine()->getManager();
            $prod = $entityManager->getRepository(CotizacionesProductos::class)->findBy(['id_cotizacion' => $c->getId()]);
            $precio = 0;
            $costo = 0;
            
            foreach($prod as $p){
                if($c->getMantenerPrecio()){
                    // SI MANTIENE EL PRECIO, SACO COSTO Y PRECIO FINAL DE LA TABLA cotizaciones_productos
                    $precio += $p->getPrecio() * $p->getCantidad();
                    $costo += $p->getCosto() * $p->getCantidad();
                }else{
                    $precio += $p->getIdProducto()->getPrecioFinal() * $p->getCantidad();
                    $costo += $p->getIdProducto()->getCosto() * $p->getCantidad();
                }
            }

            $cotizaciones[$i]['id'] = $c->getId();
            $cotizaciones[$i]['fecha'] = $c->getFecha();
            $cotizaciones[$i]['hasta'] = $c->getHasta();
            $cotizaciones[$i]['idCliente'] = $c->getIdCliente();
            $cotizaciones[$i]['creador'] = $c->getCreador();
            $cotizaciones[$i]['almacen'] = $c->getAlmacen();
            $cotizaciones[$i]['estado'] = $c->getEstado();
            $cotizaciones[$i]['precio'] = $precio;
            $cotizaciones[$i]['costo'] = $costo;
            $cotizaciones[$i]['slug'] = $c->getSlug();

            $i++;
        }

        

        return $this->render('cotizaciones/index.html.twig', [
            'cotizaciones' => $cotizaciones,
        ]);
    }

    /**
     * @Route("/new", name="cotizaciones_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('nueva_cotizacion', $request->request->get('_token'))) {
            //$descuento = $request->request->get('cotizacion')['descuento'];
            //if (!is_numeric ($request->request->get('cotizacion')['descuento'])) $descuento = 0;
            $descuento = 0;

            $cotizacion = new Cotizaciones();
            $cotizacion->setFecha(new \DateTime($request->request->get('cotizacion')['desde']));
            $cotizacion->setHasta(new \DateTime($request->request->get('cotizacion')['hasta']));
            $cotizacion->setIdCliente($entityManager->getRepository(Clientes::class)->find($request->request->get('cotizacion')['cliente']));
            $cotizacion->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('cotizacion')['almacen']));
            $cotizacion->setEstado($request->request->get('cotizacion')['estado']);
            $cotizacion->setDescuento(0);
            //$cotizacion->setDescuento((float)$descuento);
            $cotizacion->setSlug(date('U'));
            $cotizacion->setCreador($this->getUser());
            $cotizacion->setMantenerPrecio($request->request->get('cotizacion')['mantener_precio'] ?? 0);
            $entityManager->persist($cotizacion);
            $entityManager->flush();


            $mensajeProductos = $this->agregarProductosCotizacion($cotizacion, $request->request->get('productoAsignado'), $request->request->get('productoReservadoStock'));

            if($mensajeProductos['error'] != 0){
                return $this->redirectToRoute('cotizaciones_edit', ['id' => $cotizacion->getId(), 'mensajeError' => $mensajeProductos['mensaje']]);
            }else{
                return $this->redirectToRoute('cotizaciones_index');
            }

            /*            
            if($request->request->get('guardar') == '1')
                return $this->redirectToRoute('cotizaciones_index');
            elseif($request->request->get('guardar') == '2')
                return $this->redirectToRoute('cotizaciones_productos', ['id' => $cotizacion->getId()]);
            */
        }

        
        return $this->render('cotizaciones/new.html.twig', [
            'clientes' => $entityManager->getRepository(Clientes::class)->findBy(array('habilitado' => '1', 'eliminado' => '0'), array('apellido' => 'ASC', 'nombre' => 'ASC')),
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => Array()
        ]);
    }

    /**
     * @Route("/{id}", name="cotizaciones_show", methods={"GET"})
     */
    public function show(Cotizaciones $cotizacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $clienteCompleto = '';

        if($cotizacion->getIdCliente() !== null)
            $clienteCompleto = $cotizacion->getIdCliente()->getDni().' - '.$cotizacion->getIdCliente()->getApellido().', '.$cotizacion->getIdCliente()->getNombre().' - '.$cotizacion->getIdCliente()->getMail();

        $productosAsignados = Array();
        $i = 0;
        $precioTotal = 0;
        foreach($entityManager->getRepository(CotizacionesProductos::class)->findBy(['id_cotizacion' => $cotizacion->getId()]) as $p){
            $productosAsignados[$i]['id'] = $p->getIdProducto()->getId();
            $productosAsignados[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            
            if($cotizacion->getMantenerPrecio())
                $precio = $p->getPrecio();
            else
                $precio = $p->getIdProducto()->getPrecioFinal();

            $productosAsignados[$i]['precio'] = $precio;
            $productosAsignados[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getIdProducto()->getId(), 'id_almacen' => $cotizacion->getAlmacen()])->getStock();
            $productosAsignados[$i]['cantidad'] = $p->getCantidad();
            $productosAsignados[$i]['unidad'] = $p->getIdProducto()->getIdUnidadMedida()->getCorto();
            $productosAsignados[$i]['reservado'] = $p->getReservado();
            $precioTotal += $p->getCantidad() * $precio;
            $i++;
        }

        return $this->render('cotizaciones/show.html.twig', [
            'cotizacion' => $cotizacion,
            'clienteCompleto' => $clienteCompleto,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => $productosAsignados,
            'precioTotal' => $precioTotal
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cotizaciones_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cotizaciones $cotizacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('editar_cotizacion_'.$cotizacion->getId(), $request->request->get('_token'))) {
            
            //$descuento = $request->request->get('cotizacion')['descuento'];
            //if (!is_numeric ($request->request->get('cotizacion')['descuento'])) $descuento = 0;
            $descuento = 0;

            $cotizacion->setFecha(new \DateTime($request->request->get('cotizacion')['desde']));
            $cotizacion->setHasta(new \DateTime($request->request->get('cotizacion')['hasta']));
            $cotizacion->setIdCliente($entityManager->getRepository(Clientes::class)->find($request->request->get('cotizacion')['cliente']));
            $cotizacion->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('cotizacion')['almacen']));
            $cotizacion->setEstado($request->request->get('cotizacion')['estado']);
            //$cotizacion->setDescuento((float)$descuento);
            $cotizacion->setDescuento(0);
            $cotizacion->setCreador($this->getUser());
            $cotizacion->setMantenerPrecio($request->request->get('cotizacion')['mantener_precio'] ?? 0);
            $entityManager->flush();

            $mensajeProductos = $this->agregarProductosCotizacion($cotizacion, $request->request->get('productoAsignado'), $request->request->get('productoReservadoStock'));

            if($mensajeProductos['error'] != 0){
                return $this->redirectToRoute('cotizaciones_edit', ['id' => $cotizacion->getId(), 'mensajeError' => $mensajeProductos['mensaje']]);
            }else{
                return $this->redirectToRoute('cotizaciones_index');
            }
        }
        
        $clienteCompleto = '';

        if($cotizacion->getIdCliente() !== null)
            $clienteCompleto = $cotizacion->getIdCliente()->getDni().' - '.$cotizacion->getIdCliente()->getApellido().', '.$cotizacion->getIdCliente()->getNombre().' - '.$cotizacion->getIdCliente()->getMail();

        $productosAsignados = Array();
        $i = 0;
        $precioTotal = 0;
        foreach($entityManager->getRepository(CotizacionesProductos::class)->findBy(['id_cotizacion' => $cotizacion->getId()]) as $p){
            $productosAsignados[$i]['id'] = $p->getIdProducto()->getId();
            $productosAsignados[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            $reglas = $this->getReglasPrecioProductos($p->getIdProducto()->getId(), $p->getCantidad(), $p->getIdProducto()->getPrecioFinal());
            
            if($cotizacion->getMantenerPrecio())
                $precio = $p->getPrecio();
            else
                $precio = $reglas['precio']; //$precio = $p->getIdProducto()->getPrecioFinal();


            $productosAsignados[$i]['precio'] = $precio;
            $productosAsignados[$i]['precioUnitario'] = $p->getIdProducto()->getPrecioFinal();
            $productosAsignados[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getIdProducto()->getId(), 'id_almacen' => $cotizacion->getAlmacen()])->getStock();
            $productosAsignados[$i]['cantidad'] = $p->getCantidad();
            $productosAsignados[$i]['unidad'] = $p->getIdProducto()->getIdUnidadMedida()->getCorto();
            $productosAsignados[$i]['reservado'] = $p->getReservado();
            $productosAsignados[$i]['reglas'] = $reglas['reglasConcat'];
            $precioTotal += $p->getCantidad() * $precio;
            $i++;
        }

        return $this->render('cotizaciones/edit.html.twig', [
            'cotizacion' => $cotizacion,
            'clienteCompleto' => $clienteCompleto,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => $productosAsignados,
            'precioTotal' => $precioTotal,
            'mensajeError' => $request->get('mensajeError')
        ]);
    }

    /**
     * @Route("/{id}/productos", name="cotizaciones_productos", methods={"GET","POST"})
     */
    public function cotizacionesProductos_OLD(Request $request, Cotizaciones $cotizacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(CotizacionesProductos::class)->findBy(['id_cotizacion' => $cotizacion->getId()]);
        $productos = Array();
        $i = 0;
        $mantenerPrecio = $cotizacion->getMantenerPrecio();
        
        foreach($prod as $p){
            $productos[$i]['idProducto'] = $p->getIdProducto()->getId();
            $productos[$i]['codigoBarras'] = $p->getIdProducto()->getCodigoBarras();
            $productos[$i]['categoria'] = $p->getIdProducto()->getCategoria()->getNombre();
            $productos[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            if($cotizacion->getMantenerPrecio()){
                $productos[$i]['costo'] = $p->getCostoActual();
                $productos[$i]['precio'] = $p->getPrecioActual();
            }else{
                $productos[$i]['costo'] = $p->getIdProducto()->getCosto();
                $productos[$i]['precio'] = $p->getIdProducto()->getPrecioFinal();
            }
            
            $productos[$i]['cantidad'] = $p->getCantidad();
            $productos[$i]['unidad'] = $p->getIdProducto()->getIdUnidadMedida()->getCorto();
            $productos[$i]['reservaMercaderia'] = $p->getReservaMercaderia();
            $i++;
        }
        return $this->render('cotizaciones_productos/new.html.twig', [
            'cotizacion' => $cotizacion,
            'productos' => $productos
        ]);
    }

    /**
     * @Route("/{id}", name="cotizaciones_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cotizaciones $cotizacione): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cotizacione->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cotizacione);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cotizaciones_index');
    }

    /**
     * @Route("/asignar", name="cotizaciones_productos_asignar", methods={"GET","POST"})
     */
    public function productosAsignar(Request $request): Response
    {
        $mensaje = '';
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $cot = $entityManager->getRepository(Cotizaciones::class)->find($request->get('idCotizacion'));
        $cotProd = $entityManager->getRepository(CotizacionesProductos::class);
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $prod, 'id_almacen' => $cot->getAlmacen()]);
        $cant = $request->get('cantidad');
        $res = $request->get('reserva');

        $cp = $cotProd->findOneBy(
            ['id_producto' => $prod, 'id_cotizacion' => $cot]
        );

        if($cp === null) { 
            
            // Si el producto NO existe lo cargo
            // Si reserva mercaderia reviso q haya stock, si hay lo reservo, sino devuelvo mensaje y cambio el valor del campo "reservar" a 0

            $cp = new CotizacionesProductos();
            $cp->setIdProducto($prod);
            $cp->setIdCotizacion($cot);
            $cp->setCantidad($cant);
            $cp->setPrecioActual($prod->getPrecioFinal());
            $cp->setCostoActual($prod->getCosto());

            if($res == 1){
               $stock = $prodAlm->getStock();
               if($stock >= $cant){
                   // RESERVA MERCADERIA
                   $cp->setReservaMercaderia(1);
                   $stockFinal = $stock - $cant;
                   $prodAlm->setStock($stockFinal);
               }else{
                   // NO RESERVA MERCADERIA POR STOCK INSUFICIENTE
                   $cp->setReservaMercaderia(0);
                   $mensaje = 'No se pudo reservar la mercaderia por falta de stock en el almacen.';
               }
            }else{
                // NO RESERVA MERCADERIA
                $cp->setReservaMercaderia(0);
            }            
            $entityManager->persist($cp);
            $entityManager->flush();
        }else{
            // Si el producto estaba asignado actualizo cantidad
            // Si cambia el estado de reservar mercaderia, reviso y hago ajuste de stock

            if($cp->getReservaMercaderia() != $res){
                $stock = $prodAlm->getStock();
                if($cp->getReservaMercaderia() == 1){
                    // Si ya no reserva, sumo lo reservado al stock
                    $stockFinal = $stock + $cp->getCantidad();
                    $prodAlm->setStock($stockFinal);
                    $cp->setReservaMercaderia(0);
                }else{
                    // Si quiere reservar reviso que haya stock disponible
                    if($stock >= $cant){
                        // RESERVA MERCADERIA
                        $cp->setReservaMercaderia(1);
                        $stockFinal = $stock - $cant;
                        $prodAlm->setStock($stockFinal);
                    }else{
                        // NO RESERVA MERCADERIA POR STOCK INSUFICIENTE
                        $cp->setReservaMercaderia(0);
                        $mensaje = 'No se pudo reservar la mercaderia por falta de stock en el almacen.';
                    }
                }
            }else{
                // REVISAR CUANDO ESTA RESERVANDO Y SOLO CAMBIA CANTIDAD!!!
                if($cp->getReservaMercaderia() == 1 and $res == 1){
                    $dif = $cp->getCantidad() - $cant;
                    $stock = $prodAlm->getStock();
                    if($prodAlm->getStock() >= $dif){
                        $stockFinal = $stock + $dif;
                        $prodAlm->setStock($stockFinal);
                    }else{
                        $cant = $cp->getCantidad();
                        $mensaje = 'No se pudo reservar la mercaderia por falta de stock en el almacen.';
                    }
                }
            }
            $cp->setCantidad($cant);
            $entityManager->flush();
        }

        $producto['id'] = $cp->getIdProducto()->getId();
        $producto['codigoBarras'] = $cp->getIdProducto()->getCodigoBarras();
        $producto['categoria'] = $cp->getIdProducto()->getCategoria()->getNombre();
        $producto['titulo'] = $cp->getIdProducto()->getTitulo();
        $producto['costo'] = $cp->getCostoActual();
        $producto['precio'] = $cp->getPrecioActual();
        $producto['cantidad'] = $cp->getCantidad();
        $producto['reservado'] = $cp->getReservaMercaderia();
        $producto['mensaje'] = $mensaje;

        return $this->json($producto);
    }
    

    /**
     * @Route("/eliminar", name="cotizaciones_productos_eliminar", methods={"GET","POST"})
     */
    public function productosEliminar(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prod = $entityManager->getRepository(Productos::class)->find($request->get('idProducto'));
        $cot = $entityManager->getRepository(Cotizaciones::class)->find($request->get('idCotizacion'));
        $pc = $entityManager->getRepository(CotizacionesProductos::class)->findOneBy(
            [
                'id_producto' => $prod,
                'id_cotizacion' => $cot
            ]
        );
        if($pc->getReservaMercaderia()){
            // Si estaba reservando mercaderia la devuelvo al stock
            $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $prod, 'id_almacen' => $cot->getAlmacen()]);
            $stockFinal = $prodAlm->getStock() + $pc->getCantidad();
            $prodAlm->setStock($stockFinal);
        }
        $entityManager->remove($pc);
        $entityManager->flush();
        return $this->json('OK!');
    }

    /**
     * @Route("/{id}/xls", name="cotizaciones_xls", methods={"GET","POST"})
     */
    public function cotizacionesXls(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $idCotizacion = $request->attributes->get('id');
        $cot = $entityManager->getRepository(Cotizaciones::class)->find($idCotizacion);
        $cliente = ($cot->getIdCliente()) ? $cot->getIdCliente()->getApellido().', '.$cot->getIdCliente()->getNombre() : 'Anónimo';
        $prod = $entityManager->getRepository(CotizacionesProductos::class)->findBy(['id_cotizacion' => $idCotizacion]);
        $productos = Array();
        $i = 0;
        $precioFinal = 0;
        foreach($prod as $p){
            $productos[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            if($cot->getMantenerPrecio()){
                $precioFinal += $p->getPrecioActual() * $p->getCantidad();
            }else{
                $precioFinal += $p->getIdProducto()->getPrecioFinal() * $p->getCantidad() ;
            }
            
            $productos[$i]['cantidad'] = number_format($p->getCantidad(),0,',','.').' '.$p->getIdProducto()->getIdUnidadMedida()->getCorto();
            $i++;
        }
        $precioFinal = '$ '.number_format($precioFinal,2,',','.');

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Cotización Jasa Sublimación');
        $sheet->getCell('A1')->setValue('Fecha de cotización');
        $sheet->getCell('B1')->setValue($cot->getFecha()->format('d/m/Y'));
        $sheet->getCell('A2')->setValue('Válido hasta');
        $sheet->getCell('B2')->setValue($cot->getHasta()->format('d/m/Y'));
        $sheet->getCell('A3')->setValue('Cliente');
        $sheet->getCell('B3')->setValue($cliente);
        $sheet->getCell('A4')->setValue('Precio final');
        $sheet->getCell('B4')->setValue($precioFinal);
        $sheet->getCell('A5')->setValue('Detalle');
        $sheet->getCell('A6')->setValue('Producto');
        $sheet->getCell('B6')->setValue('Cantidad');
        $sheet->fromArray($productos,null, 'A7', true);
        
        $writer = new Xlsx($spreadsheet);
        
        $nombre = $idCotizacion."-Cotizacion_Jasa_Sublimacion.xlsx";
        $writer->save('cotizaciones/'.$nombre);
        $rutaDescarga = 'http://'.$request->server->get('HTTP_HOST').'/cotizaciones/'.$nombre;
        header('Location: '.$rutaDescarga);
        exit();
    }

    // NUEVOS
    /**
     * @Route("/productos", name="cotizaciones_productos", methods={"GET","POST"})
     */
    public function cotizacionesProductos(Request $request): Response
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

    public function agregarProductosCotizacion(Cotizaciones $cotizacion, $prodCant, $prodReservados){
        $entityManager = $this->getDoctrine()->getManager();
        $ret['error'] = 0;
        $ret['mensaje'] = Array();

        if($prodReservados === null)
            $prodReservados = Array();

        // REPONER STOCK EN CASO DE QUE SEA MODIFICACION CON RESERVA PARA BORRAR LA TABLA cotizaciones_productos
        $asignadosOld = $entityManager->getRepository(CotizacionesProductos::class)->findBy(['id_cotizacion' => $cotizacion->getId()]);
        foreach($asignadosOld as $aO){
            if($aO->getReservado() == 1){ // Solo repone stock cuando estaba reservado
                $reponer = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $aO->getIdProducto(), 'id_almacen' => $cotizacion->getAlmacen()]);
                $stockActual = $reponer->getStock() + $aO->getCantidad();
                $reponer->setStock($stockActual);
            }
            $entityManager->remove($aO);
            $entityManager->flush();
        }

        // ASIGNO LOS PRODUCTOS 
        $i = 0;
        foreach(array_keys($prodCant) as $idProd){
            $producto = $entityManager->getRepository(Productos::class)->find($idProd);

            if($prodCant[$idProd] == '')
                $prodCant[$idProd] = 0;

            $reglas = $this->getReglasPrecioProductos($idProd, $prodCant[$idProd], $producto->getPrecioFinal());

            $cp = new CotizacionesProductos();
            $cp->setIdCotizacion($cotizacion);
            $cp->setIdProducto($producto);
            $cp->setCantidad($prodCant[$idProd]);
            $cp->setPrecio($reglas['precio']);
            $cp->setCosto($producto->getCosto());
            $cp->setReservado(0);

            // SI QUERE RESERVAR STOCK REVISO QUE HAYA DISPONIBILIDAD Y RESERVO O DEVUELVO MENSAJE
            if(in_array($idProd, $prodReservados)){
                $prodActual = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $idProd, 'id_almacen' => $cotizacion->getAlmacen()]);

                $prodStockNuevo = $prodActual->getStock() - $prodCant[$idProd];

                if($prodStockNuevo >= 0){
                    $cp->setReservado(1);
                    $prodActual->setStock($prodStockNuevo);
                }else{
                    $ret['error'] = 1;
                    $ret['mensaje'][$i]  = "No hay suficiente stock del producto ".$producto->getTitulo();
                    $ret['mensaje'][$i] .= " (cantidad solicitada: ".number_format($prodCant[$idProd],0,',','.');
                    $ret['mensaje'][$i] .= $producto->getIdUnidadMedida()->getCorto().", ";
                    $ret['mensaje'][$i] .= "stock actual: ".number_format($prodActual->getStock(),0,',','.');
                    $ret['mensaje'][$i] .= $producto->getIdUnidadMedida()->getCorto().")";
                    $i++;
                }
            }

            $entityManager->persist($cp);
            $entityManager->flush();
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
     * @Route("/{id}/venta", name="cotizaciones_venta", methods={"GET","POST"})
     */
    public function venta(Request $request, Cotizaciones $cotizacion): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // SI LA CAJA ESTA CERRADA MANDO A FRM CAJA
        $cajaAbiertaHoy = $entityManager->getRepository(Caja::class)->findCajaAbiertaHoy();
        if(count($cajaAbiertaHoy) == 0)
            return $this->redirectToRoute('caja_index');

        $clienteCompleto = '';

        if($cotizacion->getIdCliente() !== null)
            $clienteCompleto = $cotizacion->getIdCliente()->getDni().' - '.$cotizacion->getIdCliente()->getApellido().', '.$cotizacion->getIdCliente()->getNombre().' - '.$cotizacion->getIdCliente()->getMail();

        $productosAsignados = Array();
        $i = 0;
        $precioTotal = 0;
        foreach($entityManager->getRepository(CotizacionesProductos::class)->findBy(['id_cotizacion' => $cotizacion->getId()]) as $p){
            $productosAsignados[$i]['id'] = $p->getIdProducto()->getId();
            $productosAsignados[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            
            if($cotizacion->getMantenerPrecio())
                $precio = $p->getPrecio();
            else
                $precio = $p->getIdProducto()->getPrecioFinal();

            $productosAsignados[$i]['precio'] = $precio;
            $productosAsignados[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getIdProducto()->getId(), 'id_almacen' => $cotizacion->getAlmacen()])->getStock();
            $productosAsignados[$i]['cantidad'] = $p->getCantidad();
            $productosAsignados[$i]['unidad'] = $p->getIdProducto()->getIdUnidadMedida()->getCorto();
            $productosAsignados[$i]['reservado'] = $p->getReservado();
            $precioTotal += $p->getCantidad() * $precio;
            $i++;
        }

        // DATOS CLIENTE
        $cliente = Array(
            'nombre' => ($cotizacion->getIdCliente()) ? $cotizacion->getIdCliente()->getNombre() : 'Anónimo',
            'apellido' => ($cotizacion->getIdCliente()) ? $cotizacion->getIdCliente()->getApellido() : '',
            'mail' => ($cotizacion->getIdCliente()) ? $cotizacion->getIdCliente()->getMail() : '',
            'telefono' => ($cotizacion->getIdCliente()) ? $cotizacion->getIdCliente()->getTelefono() : '',
            'direccion' => ($cotizacion->getIdCliente()) ? $cotizacion->getIdCliente()->getDireccion() : '',
            'id' => ($cotizacion->getIdCliente()) ? $cotizacion->getCliente()->getId() : 0
        );

        return $this->render('cotizaciones/venta.html.twig', [
            'cotizacion' => $cotizacion,
            'clienteCompleto' => $clienteCompleto,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => $productosAsignados,
            'precioTotal' => $precioTotal,
            'cliente' => $cliente
        ]);
    }
}
