<?php

namespace App\Controller;

use App\Entity\Combos;
use App\Entity\Almacenes;
use App\Entity\ProductosAlmacenes;
use App\Form\CombosType;
use App\Repository\CombosRepository;
use App\Entity\CombosProductos;
use App\Form\CombosProductosType;
use App\Repository\CombosProductosRepository;
use App\Entity\Productos;
use App\Entity\ReglasPreciosProductos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/combos")
 */
class CombosController extends AbstractController
{
    /**
     * @Route("/", name="combos_index", methods={"GET"})
     */
    public function index(CombosRepository $combosRepository): Response
    {
        return $this->render('combos/index.html.twig', [
            'combos' => $combosRepository->findAllVigentes(),
        ]);
    }

    /**
     * @Route("/new", name="combos_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('nuevo_combo', $request->request->get('_token'))) {

            $com = new Combos();
            $com->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('combo')['almacen']));
            $com->setNombre($request->request->get('combo')['titulo']);
            $com->setPorcentajeCosto($request->request->get('combo')['porcentaje_costo']);
            $com->setPrecioFinal($request->request->get('combo')['precio_final']);
            $com->setDescripcion($request->request->get('combo')['descripcion']);
            $com->setHabilitado($request->request->get('combo')['habilitado'] ?? 0);
            $entityManager->persist($com);
            $entityManager->flush();

            // AGREGO PRODUCTOS AL COMBO
            $this->agregarProductosCombo($com, $request->request->get('productoAsignado'));

            //return $this->redirectToRoute('combos_index');
            return $this->redirectToRoute('combos_edit', Array('id' => $com->getId()));
        }
        return $this->render('combos/new.html.twig', [
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => Array()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="combos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Combos $combo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        if ($this->isCsrfTokenValid('editar_combo_'.$combo->getId(), $request->request->get('_token'))) {

            $combo->setAlmacen($entityManager->getRepository(Almacenes::class)->find($request->request->get('combo')['almacen']));
            $combo->setNombre($request->request->get('combo')['titulo']);
            $combo->setPorcentajeCosto($request->request->get('combo')['porcentaje_costo']);
            $combo->setPrecioFinal($request->request->get('combo')['precio_final']);
            $combo->setDescripcion($request->request->get('combo')['descripcion']);
            $combo->setHabilitado($request->request->get('combo')['titulo'] ?? 0);
            $entityManager->flush();

            // AGREGO PRODUCTOS AL COMBO
            $this->agregarProductosCombo($combo, $request->request->get('productoAsignado'));

            //return $this->redirectToRoute('combos_index');
            return $this->redirectToRoute('combos_edit', Array('id' => $combo->getId()));
        }

        $productosAsignados = Array();
        $i = 0;
        $precioTotal = 0;
        foreach($entityManager->getRepository(CombosProductos::class)->findBy(['id_combo' => $combo->getId()]) as $p){

            $reglas = $this->getReglasPrecioProductos($p->getIdProducto()->getId(), $p->getCantidad(), $p->getIdProducto()->getPrecioFinal());

            $productosAsignados[$i]['id'] = $p->getIdProducto()->getId();
            $productosAsignados[$i]['titulo'] = $p->getIdProducto()->getTitulo();
            $productosAsignados[$i]['costo'] = $p->getIdProducto()->getCosto();
            $productosAsignados[$i]['precio'] = $reglas['precio'];
            $productosAsignados[$i]['precioUnitario'] = $p->getIdProducto()->getPrecioFinal();
            $productosAsignados[$i]['stockActual'] = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $p->getIdProducto()->getId(), 'id_almacen' => $combo->getAlmacen()])->getStock();
            $productosAsignados[$i]['cantidad'] = $p->getCantidad();
            $productosAsignados[$i]['unidad'] = $p->getIdProducto()->getIdUnidadMedida()->getCorto();
            $productosAsignados[$i]['reglas'] = $reglas['reglasConcat'];
            $precioTotal += $p->getCantidad() * $reglas['precio'];
            $i++;
        }

        $mensajeStock = $this->validaStockCombos($combo);

        return $this->render('combos/edit.html.twig', [
            'combo' => $combo,
            'almacenes' => $entityManager->getRepository(Almacenes::class)->findAllVigentes(),
            'productosAsignados' => $productosAsignados,
            'precioTotal' => $precioTotal,
            'mensajeStock' => $mensajeStock
        ]);
    }

     /**
     * @Route("/{id}", name="combos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Combos $combo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$combo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $comb = $entityManager->getRepository(Combos::class)->find($combo->getId());
            $comb->setEliminado(1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('combos_index');
    }

    public function validaStockCombos($combo){
        $entityManager = $this->getDoctrine()->getManager();

        $idCombo = $combo->getId();
        $idAlmacen = $combo->getAlmacen()->getId();
        $stockProductos = $entityManager->getRepository(Combos::class)->getStockProductosCombo($idCombo, $idAlmacen);
        $sp = Array();
        $errorStock = 0;
        $mensajeErrorStock = Array();
        $cantCombos = 0;

        foreach($stockProductos as $prods){ // Armo array de stock con key = id_producto
            $sp[$prods['id']]['titulo'] = $prods['titulo'];
            $sp[$prods['id']]['stock'] = intval($prods['stock']);
        }

        foreach($combo->getCombosProductos() as $cp){
            $um = $cp->getIdProducto()->getIdUnidadMedida()->getCorto();
            $stockActual = $sp[$cp->getIdProducto()->getId()]['stock'];
            $cantidad = $cp->getCantidad();
            $producto = $sp[$cp->getIdProducto()->getId()]['titulo'];
            
            if($cantidad > $stockActual){
                // SI NO HAY SUFICIENTE STOCK AGREGO EL MENSAJE DE ERROR
                $msj  = "<p style='color:var(--rojo);'><b>NO hay suficiente stock de ".$producto.".</b> ";
                $msj .= " <br/>&nbsp;&nbsp;&nbsp;Cantidad necesaria: ".$cantidad." ".$um;
                $msj .= " <br/>&nbsp;&nbsp;&nbsp;Stock actual: ".$stockActual." ".$um;
                $msj .= " <br/>&nbsp;&nbsp;&nbsp;Faltante: ".($cantidad - $stockActual)." ".$um."</p>";
                $errorStock = 1;
                $mensajeErrorStock[count($mensajeErrorStock)] = $msj;
            }else{
                // SI HAY STOCK SUFICIENTE VEO PARA CUANTOS ALCANZA
                $alcanza = $stockActual / $cantidad;
                if($alcanza < $cantCombos or $cantCombos == 0)
                    $cantCombos = $alcanza;
            }
        }
        if($errorStock == 0)
            $mensajeErrorStock[0] = "<p style='color:var(--verde);font-weight:bold;'>Hay suficiente stock para ".number_format($cantCombos, 0,'.',',')." combos.</p>";

        return $mensajeErrorStock;
    }

    // PRODUCTOS

    // NUEVOS
    /**
     * @Route("/productos", name="combos_productos", methods={"GET","POST"})
     */
    public function combosProductos(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $prodAlm = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $request->get('idProducto'), 'id_almacen' => $request->get('idAlmacen')]);
        
        $reglas = $this->getReglasPrecioProductos($request->get('idProducto'));
        
        $res['id'] = $prodAlm->getIdProducto()->getId();
        $res['titulo'] = $prodAlm->getIdProducto()->getTitulo();
        $res['costo'] = $prodAlm->getIdProducto()->getCosto();
        $res['precio'] = $prodAlm->getIdProducto()->getPrecioFinal();
        $res['stock'] = $prodAlm->getStock();
        $res['um'] = $prodAlm->getIdProducto()->getIdUnidadMedida()->getCorto();
        $res['reglaprecios'] = $reglas['reglasConcat'];

        return $this->json($res);
    }

    public function agregarProductosCombo(Combos $combo, $prodCant){
        $entityManager = $this->getDoctrine()->getManager();

        if($prodCant === null)
            $prodCant = Array();

        // ELIMINO LOS PRODUCTOS VIEJOS PARA AGREGAR LOS NUEVOS
        $entityManager->getRepository(Combos::class)->eliminarProductosCombo($combo->getId());
        
        // ASIGNO LOS PRODUCTOS DE VUELTA CON LA CANTIDAD QUE CORRESPONDE. SI NO HAY STOCK SUFICIENTE GUARDO EL RESTO Y MANDO MENSAJE
        $pc = Array();
        $i = 0;
        foreach(array_keys($prodCant) as $idProd){
            $producto = $entityManager->getRepository(Productos::class)->find($idProd);
            
            if($prodCant[$idProd] == '')
                $prodCant[$idProd] = 0;

            $cp = new combosProductos();
            $cp->setIdProducto($producto);
            $cp->setIdCombo($combo);
            $cp->setCantidad($prodCant[$idProd]);
            $entityManager->persist($cp);
            $entityManager->flush();
        }
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

    // IMAGENES
    /**
     * @Route("/{id}/imagenes", name="combos_imagenes", methods={"GET","POST"})
     */
    public function imagenes(Request $request, Combos $combo): Response
    {
        $ID = $combo->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/combos/'.$ID.'/';

        if ($this->isCsrfTokenValid('nueva_imagen_'.$ID, $request->request->get('_token'))) {

            $file = $request->files->get('imagen');
            $ahora = new \DateTime();
            $nombre = $ahora->format('U').'.'.$file->guessExtension();
            
            $file->move($ubicacion, $nombre);

            return $this->redirectToRoute('combos_imagenes', ['id' => $ID]);
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

        return $this->render('combos/imagenes.html.twig', [
            'combo' => $combo,
            'imagenes' => $imagenes,
            'rutaImagenes' => 'images/combos/'.$ID.'/'
        ]);
    }

    /**
     * @Route("/{id}/{imagen}/imagenes/borrar", name="combos_imagenes_borrar", methods={"GET","POST"})
     */
    public function imagenesBorrar(Request $request, Combos $combo, $imagen): Response
    {
        $ID = $combo->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/combos/'.$ID.'/';
        $file = $ubicacion.$imagen;
  
        $filesystem = new Filesystem();
        if($filesystem->exists($file)){
            $filesystem->remove($file);
        }

        return $this->redirectToRoute('combos_imagenes', ['id' => $ID]);
    }

}
