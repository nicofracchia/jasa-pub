<?php

namespace App\Controller;

use App\Entity\Productos;
use App\Entity\Categorias;
use App\Entity\Almacenes;
use App\Entity\ProductosAlmacenes;
use App\Entity\CategoriasProductos;
use App\Entity\UnidadesMedida;
use App\Entity\ReglasPreciosProductos;
use App\Form\ProductosType;
use App\Repository\ProductosRepository;
use App\Repository\CategoriasRepository;
use App\Repository\UnidadesMedidaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/productos")
 */
class ProductosController extends AbstractController
{
    /**
     * @Route("/", name="productos_index", methods={"GET", "POST"})
     */
    public function index(Request $request, ProductosRepository $productosRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $filtros = Array('titulo' => '', 'id' => '', 'codigo_barras' => '', 'origen' => '');

        if(isset($request->request->get('filtroProductos')['titulo']))
            $filtros['titulo'] = $request->request->get('filtroProductos')['titulo'];
        if(isset($request->request->get('filtroProductos')['id']))
            $filtros['id'] = $request->request->get('filtroProductos')['id'];
        if(isset($request->request->get('filtroProductos')['codigo_barras']))
            $filtros['codigo_barras'] = $request->request->get('filtroProductos')['codigo_barras'];
        if(isset($request->request->get('filtroProductos')['origen']))
            $filtros['origen'] = $request->request->get('filtroProductos')['origen'];

        $data['productos'] = $productosRepository->findAllVigentes($filtros);
        $data['filtrosAplicados'] = $filtros;

        return $this->render('productos/index.html.twig', $data);
    }

    /**
     * @Route("/listado/json", name="productos_json", methods={"POST"})
     */
    public function index_json(Request $request, ProductosRepository $productosRepository): Response
    {
        $filtros['titulo'] = $request->request->get('filtroProductos')['titulo'];
        $filtros['id'] = $request->request->get('filtroProductos')['id'];
        $filtros['codigo_barras'] = $request->request->get('filtroProductos')['codigo_barras'];

        $prod = array();
        $i = 0;

        foreach($productosRepository->findAllVigentes($filtros) as $p){
            $prod[$i]['ID'] = $p[0]->getId();
            $prod[$i]['codigo_barras'] = $p[0]->getCodigoBarras();
            $prod[$i]['titulo'] = $p[0]->getTitulo();
            $prod[$i]['stock'] = $p['stock'];
            $prod[$i]['costo'] = $p[0]->getCosto();
            $prod[$i]['porcentaje'] = $p[0]->getPorcentajeCosto();
            $prod[$i]['precio'] = $p[0]->getPrecioFinal();
            $prod[$i]['unidad_medida'] = $p[0]->getIdUnidadMedida()->getNombre();
            $prod[$i]['unidad_medida_corto'] = $p[0]->getIdUnidadMedida()->getCorto();

            $i++;
        }

        return $this->json($prod);
    }

    /**
     * @Route("/new", name="productos_new_manual", methods={"GET","POST"})
     */
    public function newManual(Request $request): Response
    {
        if ($this->isCsrfTokenValid('nuevo_producto', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            // UNIDAD DE MEDIDA
            $um = $request->request->get('productos')['id_unidad_medida'];
            $um = $entityManager->getRepository(UnidadesMedida::class)->find($um);

            // GUARDO EL PRODUCTO Y LO USO PARA ASIGNAR ALMACENES
            $prod = new Productos();
            $prod->setTitulo($request->request->get('productos')['titulo']);
            $prod->setCodigoBarras(0);
            $prod->setCosto($request->request->get('productos')['costo']);
            $prod->setPorcentajeCosto($request->request->get('productos')['porcentaje_costo']);
            $prod->setPrecioFinal($request->request->get('productos')['precio_final']);
            $prod->setIva($request->request->get('productos')['iva']);
            $prod->setStockActual(0);
            $prod->setStockMinimo($request->request->get('productos')['stock_minimo']);
            $prod->setIdUnidadMedida($um);
            $prod->setHabilitado($request->request->get('productos')['habilitado'] ?? 0);
            $prod->setMaterialReparacion($request->request->get('productos')['material_reparacion'] ?? 0);
            $prod->setDescripcion($request->request->get('productos')['descripcion']);
            $prod->setDiametro(floatval($request->request->get('productos')['diametro']));
            $prod->setlargo(floatval($request->request->get('productos')['largo']));
            $prod->setAncho(floatval($request->request->get('productos')['ancho']));
            $prod->setColor($request->request->get('productos')['color']);
            $prod->setMaterial($request->request->get('productos')['material']);
            $prod->setUtilidad($request->request->get('productos')['utilidad']);
            $prod->setPresentacion($request->request->get('productos')['presentacion']);
            $entityManager->persist($prod);
            $entityManager->flush();

            // ASIGNO ALMACENES AL PRODUCTO NUEVO
            foreach ($request->request->get('almacenes') as $idAlmacen){
                $almacen = $entityManager->getRepository(Almacenes::class)->find($idAlmacen);

                $PA = new ProductosAlmacenes();
                $PA->setIdProducto($prod);
                $PA->setIdAlmacen($almacen);
                $PA->setStock(0);
                $entityManager->persist($PA);
                $entityManager->flush();
            }

            // ASIGNO CATEGORIAS AL PRODUCTO NUEVO
            if($request->request->get('categoriasProducto') !== null){
                foreach ($request->request->get('categoriasProducto') as $idCategoria){
                    $cat = $entityManager->getRepository(Categorias::class)->find($idCategoria);
                    $CP = new CategoriasProductos();
                    $CP->setProducto($prod);
                    $CP->setCategoria($cat);
                    $entityManager->persist($CP);
                    $entityManager->flush();
                }
            }

            // ASIGNO REGLAS DE PRECIOS AL PRODUCTO NUEVO
            if($request->request->get('reglasCantidad') !== null and $request->request->get('reglasPrecio') !== null)
                $this->asignarPreciosCantidad($request->request->get('reglasCantidad'), $request->request->get('reglasPrecio'), $prod);


            return $this->redirectToRoute('productos_index');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $almacenes = $entityManager->getRepository(Almacenes::class)->findAllVigentes();

        return $this->render('productos/new.html.twig', [
            'almacenes' => $almacenes
        ]);
    }

    public function asignarPreciosCantidad($cantidades, $precios, $producto){
        $entityManager = $this->getDoctrine()->getManager();

        // ELIMINO REGLAS ANTERIORES
        $entityManager->getRepository(Productos::class)->eliminarReglasProducto($producto->getId());

        // CARGO LAS NUEVAS
        foreach(array_keys($cantidades) as $idRegla){
            if($cantidades[$idRegla] > 0 && $precios[$idRegla] > 0){
                $rpp = new ReglasPreciosProductos();
                $rpp->setCantidad($cantidades[$idRegla]);
                $rpp->setPrecio($precios[$idRegla]);
                $rpp->setProducto($producto);
                $entityManager->persist($rpp);
                $entityManager->flush();
            }
        }
    }

    /**
     * @Route("/buscar", name="productos_buscar", methods={"GET","POST"})
     */
    public function buscar(Request $request, ProductosRepository $productosRepository): Response
    {
        $busqueda = explode(' ',$request->request->get('busqueda'));
        $idAlmacen = $request->request->get('idAlmacen');
        $productos = $productosRepository->findAllBusqueda($idAlmacen, $busqueda);
        return $this->json($productos);
    }

    /**
     * @Route("/buscar/material", name="productos_buscar_material", methods={"GET","POST"})
     */
    public function buscarMaterialReparacion(Request $request, ProductosRepository $productosRepository): Response
    {
        $busqueda = explode(' ',$request->request->get('busqueda'));
        $idAlmacen = $request->request->get('idAlmacen');
        $productos = $productosRepository->findAllBusquedaMateriales($idAlmacen, $busqueda);
        return $this->json($productos);
    }

    /**
     * @Route("/{id}", name="productos_show", methods={"GET"})
     */
    public function show(Productos $producto): Response
    {
        return $this->render('productos/show.html.twig', [
            'producto' => $producto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="productos_edit_manual", methods={"GET","POST"})
     */
    public function editManual(Request $request, Productos $producto): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('editar'.$producto->getId(), $request->request->get('_token'))) {

            // UNIDAD DE MEDIDA
            $um = $request->request->get('productos')['id_unidad_medida'];
            $um = $entityManager->getRepository(UnidadesMedida::class)->find($um);

            // GUARDO EL PRODUCTO Y LO USO PARA ASIGNAR ALMACENES
            $prod = $entityManager->getRepository(Productos::class)->find($producto->getId());
            $prod->setTitulo($request->request->get('productos')['titulo']);
            //$prod->setCodigoBarras($request->request->get('productos')['codigo_barras']);
            $prod->setCodigoBarras(0);
            $prod->setCosto($request->request->get('productos')['costo']);
            $prod->setPorcentajeCosto($request->request->get('productos')['porcentaje_costo']);
            $prod->setPrecioFinal($request->request->get('productos')['precio_final']);
            $prod->setIva($request->request->get('productos')['iva']);
            $prod->setStockMinimo($request->request->get('productos')['stock_minimo']);
            $prod->setIdUnidadMedida($um);
            $prod->setHabilitado($request->request->get('productos')['habilitado'] ?? 0);
            $prod->setMaterialReparacion($request->request->get('productos')['material_reparacion'] ?? 0);
            $prod->setDescripcion($request->request->get('productos')['descripcion']);
            $prod->setDiametro(floatval($request->request->get('productos')['diametro']));
            $prod->setlargo(floatval($request->request->get('productos')['largo']));
            $prod->setAncho(floatval($request->request->get('productos')['ancho']));
            $prod->setColor($request->request->get('productos')['color']);
            $prod->setMaterial($request->request->get('productos')['material']);
            $prod->setUtilidad($request->request->get('productos')['utilidad']);
            $prod->setPresentacion($request->request->get('productos')['presentacion']);
            $entityManager->flush();

            // ASIGNO REGLAS DE PRECIOS AL PRODUCTO NUEVO
            if($request->request->get('reglasCantidad') !== null and $request->request->get('reglasPrecio') !== null)
                $this->asignarPreciosCantidad($request->request->get('reglasCantidad'), $request->request->get('reglasPrecio'), $prod);

            // RELACIONO ALMACENES
            $almacenes = $entityManager->getRepository(Almacenes::class)->findAllVigentes();
            foreach($almacenes as $a){
                $productoAlmacen = $entityManager->getRepository(ProductosAlmacenes::class)->findOneBy(['id_producto' => $prod->getId(), 'id_almacen' => $a->getId()]);
                if(in_array($a->getId(), $request->request->get('almacenes'))){
                    // Si el almacen checkeado no existe en productos_almacenes lo agrego con stock 0
                    if($productoAlmacen === null){
                        $PA = new ProductosAlmacenes();
                        $PA->setIdProducto($prod);
                        $PA->setIdAlmacen($a);
                        $PA->setStock(0);
                        $entityManager->persist($PA);
                        $entityManager->flush();
                    }
                }else{
                    // Si el almacen sin checkear existe en productos_almacenes solo lo elimino cuando tiene stock = 0
                    if($productoAlmacen !== null){
                        if($productoAlmacen->getStock() == 0){
                            $entityManager->remove($productoAlmacen);
                            $entityManager->flush();
                        }
                    }
                }
            }

            // ELIMINO ASOCIACION ANTERIOR DE CATEGORIAS PARA GUARDAR LA NUEVA
            $catProd = $entityManager->getRepository(CategoriasProductos::class)->findBy(['producto' => $prod->getId()]);
            foreach($catProd as $cp){
                $entityManager->remove($cp);
                $entityManager->flush();
            }

             // ASIGNO CATEGORIAS AL PRODUCTO NUEVO
             if($request->request->get('categoriasProducto') !== null){
                foreach ($request->request->get('categoriasProducto') as $idCategoria){
                    $cat = $entityManager->getRepository(Categorias::class)->find($idCategoria);
                    $CP = new CategoriasProductos();
                    $CP->setProducto($prod);
                    $CP->setCategoria($cat);
                    $entityManager->persist($CP);
                    $entityManager->flush();
                }
            }
            
            return $this->redirectToRoute('productos_index');
            
        }

        $almacenes = $entityManager->getRepository(Almacenes::class)->findAllVigentes();
        $productosAlmacenes = $entityManager->getRepository(ProductosAlmacenes::class)->findBy(['id_producto' => $producto->getId()]);
        $pa = Array();
        foreach($productosAlmacenes as $prodAlm){
            $pa[$prodAlm->getIdAlmacen()->getId()] = $prodAlm->getStock();
        }
        $categoriasAsignadas = $entityManager->getRepository(CategoriasProductos::class)->findBy(['producto' => $producto->getId()]);
        $catAg = Array();
        $catAgJS = '0';
        $i = 0;
        foreach($categoriasAsignadas as $ca){
            $catAg[$i]['id'] = $ca->getCategoria()->getId();
            $catAg[$i]['categoria'] = $ca->getCategoria()->getNombre();
            $catAgJS .= ','.$ca->getCategoria()->getId();
            $i++;
        }
        return $this->render('productos/edit.html.twig', [
            'producto' => $producto,
            'almacenes' => $almacenes,
            'productosAlmacenes' => $pa,
            'categoriasAsignadas' => $catAg,
            'catAgJS' => $catAgJS,
            'reglasPrecios' => $entityManager->getRepository(ReglasPreciosProductos::class)->findBy(['producto' => $producto->getId()])
        ]);
    }

    /**
     * @Route("/{id}", name="productos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Productos $producto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $prod = $entityManager->getRepository(Productos::class)->find($producto->getId());
            $prod->setEliminado(1);
            $entityManager->flush();
        }

        return $this->redirectToRoute('productos_index');
    }

     /**
     * @Route("/{id}/imagenes", name="productos_imagenes", methods={"GET","POST"})
     */
    public function imagenes(Request $request, Productos $producto): Response
    {
        $ID = $producto->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/productos/'.$ID.'/';

        if ($this->isCsrfTokenValid('nueva_imagen_'.$ID, $request->request->get('_token'))) {

            $file = $request->files->get('imagen');
            $ahora = new \DateTime();
            $nombre = $ahora->format('U').'.'.$file->guessExtension();
            
            $file->move($ubicacion, $nombre);

            return $this->redirectToRoute('productos_imagenes', ['id' => $ID]);
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

        return $this->render('productos/imagenes.html.twig', [
            'producto' => $producto,
            'imagenes' => $imagenes,
            'rutaImagenes' => 'images/productos/'.$ID.'/'
        ]);
    }

    /**
     * @Route("/{id}/{imagen}/imagenes/borrar", name="productos_imagenes_borrar", methods={"GET","POST"})
     */
    public function imagenesBorrar(Request $request, Productos $producto, $imagen): Response
    {
        $ID = $producto->getId();
        $ubicacion = $this->getParameter('kernel.project_dir').'/public/images/productos/'.$ID.'/';
        $file = $ubicacion.$imagen;
  
        $filesystem = new Filesystem();
        if($filesystem->exists($file)){
            $filesystem->remove($file);
        }

        return $this->redirectToRoute('productos_imagenes', ['id' => $ID]);
    }
}
