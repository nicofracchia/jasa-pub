<?php

namespace App\Entity;

use App\Repository\ProductosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductosRepository::class)
 */
class Productos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codigo_barras;

    /**
     * @ORM\Column(type="float")
     */
    private $costo;

    /**
     * @ORM\Column(type="float")
     */
    private $porcentaje_costo;

    /**
     * @ORM\Column(type="float")
     */
    private $precio_final;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity=ProductosAlmacenes::class, mappedBy="id_producto")
     */
    private $productosAlmacenes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $habilitado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminado = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @ORM\OneToMany(targetEntity=CombosProductos::class, mappedBy="id_producto")
     */
    private $combosProductos;

    /**
     * @ORM\OneToMany(targetEntity=ProveedoresProductos::class, mappedBy="id_producto")
     */
    private $proveedoresProductos;

    /**
     * @ORM\OneToMany(targetEntity=CotizacionesProductos::class, mappedBy="id_producto")
     */
    private $cotizacionesProductos;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_actual;

    /**
     * @ORM\ManyToOne(targetEntity=UnidadesMedida::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_unidad_medida;

    /**
     * @ORM\OneToMany(targetEntity=ComprasProductos::class, mappedBy="producto")
     */
    private $comprasProductos;

    /**
     * @ORM\OneToMany(targetEntity=VentasProductos::class, mappedBy="producto")
     */
    private $ventasProductos;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_minimo;

    /**
     * @ORM\OneToMany(targetEntity=AjustesStock::class, mappedBy="producto")
     */
    private $ajustesStocks;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $diametro;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $largo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ancho;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $material;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $utilidad;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentacion;

    /**
     * @ORM\OneToMany(targetEntity=CategoriasProductos::class, mappedBy="producto")
     */
    private $categoriasProductos;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $iva;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $materialReparacion;

    /**
     * @ORM\OneToMany(targetEntity=ReparacionesProductos::class, mappedBy="producto")
     */
    private $reparacionesProductos;

    /**
     * @ORM\OneToMany(targetEntity=ReglasPreciosProductos::class, mappedBy="producto")
     */
    private $reglasPreciosProductos;

    public function __construct()
    {
        $this->productosAlmacenes = new ArrayCollection();
        $this->combosProductos = new ArrayCollection();
        $this->proveedoresProductos = new ArrayCollection();
        $this->cotizacionesProductos = new ArrayCollection();
        $this->comprasProductos = new ArrayCollection();
        $this->ventasProductos = new ArrayCollection();
        $this->ajustesStocks = new ArrayCollection();
        $this->categoriasProductos = new ArrayCollection();
        $this->reparacionesProductos = new ArrayCollection();
        $this->reglasPreciosProductos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getCodigoBarras(): ?string
    {
        return $this->codigo_barras;
    }

    public function setCodigoBarras(?string $codigo_barras): self
    {
        $this->codigo_barras = $codigo_barras;

        return $this;
    }

    public function getCosto(): ?float
    {
        return $this->costo;
    }

    public function setCosto(float $costo): self
    {
        $this->costo = $costo;

        return $this;
    }

    public function getPorcentajeCosto(): ?float
    {
        return $this->porcentaje_costo;
    }

    public function setPorcentajeCosto(float $porcentaje_costo): self
    {
        $this->porcentaje_costo = $porcentaje_costo;

        return $this;
    }

    public function getPrecioFinal(): ?float
    {
        return $this->precio_final;
    }

    public function setPrecioFinal(float $precio_final): self
    {
        $this->precio_final = $precio_final;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * @return Collection|ProductosAlmacenes[]
     */
    public function getProductosAlmacenes(): Collection
    {
        return $this->productosAlmacenes;
    }

    public function addProductosAlmacene(ProductosAlmacenes $productosAlmacene): self
    {
        if (!$this->productosAlmacenes->contains($productosAlmacene)) {
            $this->productosAlmacenes[] = $productosAlmacene;
            $productosAlmacene->setIdProducto($this);
        }

        return $this;
    }

    public function removeProductosAlmacene(ProductosAlmacenes $productosAlmacene): self
    {
        if ($this->productosAlmacenes->contains($productosAlmacene)) {
            $this->productosAlmacenes->removeElement($productosAlmacene);
            // set the owning side to null (unless already changed)
            if ($productosAlmacene->getIdProducto() === $this) {
                $productosAlmacene->setIdProducto(null);
            }
        }

        return $this;
    }

    public function getHabilitado(): ?bool
    {
        return $this->habilitado;
    }

    public function setHabilitado(bool $habilitado): self
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    public function getEliminado(): ?bool
    {
        return $this->eliminado;
    }

    public function setEliminado(bool $eliminado): self
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * @return Collection|CombosProductos[]
     */
    public function getCombosProductos(): Collection
    {
        return $this->combosProductos;
    }

    public function addCombosProducto(CombosProductos $combosProducto): self
    {
        if (!$this->combosProductos->contains($combosProducto)) {
            $this->combosProductos[] = $combosProducto;
            $combosProducto->setIdProducto($this);
        }

        return $this;
    }

    public function removeCombosProducto(CombosProductos $combosProducto): self
    {
        if ($this->combosProductos->contains($combosProducto)) {
            $this->combosProductos->removeElement($combosProducto);
            // set the owning side to null (unless already changed)
            if ($combosProducto->getIdProducto() === $this) {
                $combosProducto->setIdProducto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProveedoresProductos[]
     */
    public function getProveedoresProductos(): Collection
    {
        return $this->proveedoresProductos;
    }

    public function addProveedoresProducto(ProveedoresProductos $proveedoresProducto): self
    {
        if (!$this->proveedoresProductos->contains($proveedoresProducto)) {
            $this->proveedoresProductos[] = $proveedoresProducto;
            $proveedoresProducto->setIdProducto($this);
        }

        return $this;
    }

    public function removeProveedoresProducto(ProveedoresProductos $proveedoresProducto): self
    {
        if ($this->proveedoresProductos->contains($proveedoresProducto)) {
            $this->proveedoresProductos->removeElement($proveedoresProducto);
            // set the owning side to null (unless already changed)
            if ($proveedoresProducto->getIdProducto() === $this) {
                $proveedoresProducto->setIdProducto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CotizacionesProductos[]
     */
    public function getCotizacionesProductos(): Collection
    {
        return $this->cotizacionesProductos;
    }

    public function addCotizacionesProducto(CotizacionesProductos $cotizacionesProducto): self
    {
        if (!$this->cotizacionesProductos->contains($cotizacionesProducto)) {
            $this->cotizacionesProductos[] = $cotizacionesProducto;
            $cotizacionesProducto->setIdProducto($this);
        }

        return $this;
    }

    public function removeCotizacionesProducto(CotizacionesProductos $cotizacionesProducto): self
    {
        if ($this->cotizacionesProductos->contains($cotizacionesProducto)) {
            $this->cotizacionesProductos->removeElement($cotizacionesProducto);
            // set the owning side to null (unless already changed)
            if ($cotizacionesProducto->getIdProducto() === $this) {
                $cotizacionesProducto->setIdProducto(null);
            }
        }

        return $this;
    }

    public function getStockActual(): ?int
    {
        return $this->stock_actual;
    }

    public function setStockActual(int $stock_actual): self
    {
        $this->stock_actual = $stock_actual;

        return $this;
    }

    public function getIdUnidadMedida(): ?UnidadesMedida
    {
        return $this->id_unidad_medida;
    }

    public function setIdUnidadMedida(UnidadesMedida $id_unidad_medida): self
    {
        $this->id_unidad_medida = $id_unidad_medida;

        return $this;
    }

    /**
     * @return Collection|ComprasProductos[]
     */
    public function getComprasProductos(): Collection
    {
        return $this->comprasProductos;
    }

    public function addComprasProducto(ComprasProductos $comprasProducto): self
    {
        if (!$this->comprasProductos->contains($comprasProducto)) {
            $this->comprasProductos[] = $comprasProducto;
            $comprasProducto->setProducto($this);
        }

        return $this;
    }

    public function removeComprasProducto(ComprasProductos $comprasProducto): self
    {
        if ($this->comprasProductos->contains($comprasProducto)) {
            $this->comprasProductos->removeElement($comprasProducto);
            // set the owning side to null (unless already changed)
            if ($comprasProducto->getProducto() === $this) {
                $comprasProducto->setProducto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VentasProductos[]
     */
    public function getVentasProductos(): Collection
    {
        return $this->ventasProductos;
    }

    public function addVentasProducto(VentasProductos $ventasProducto): self
    {
        if (!$this->ventasProductos->contains($ventasProducto)) {
            $this->ventasProductos[] = $ventasProducto;
            $ventasProducto->setProducto($this);
        }

        return $this;
    }

    public function removeVentasProducto(VentasProductos $ventasProducto): self
    {
        if ($this->ventasProductos->contains($ventasProducto)) {
            $this->ventasProductos->removeElement($ventasProducto);
            // set the owning side to null (unless already changed)
            if ($ventasProducto->getProducto() === $this) {
                $ventasProducto->setProducto(null);
            }
        }

        return $this;
    }

    public function getStockMinimo(): ?int
    {
        return $this->stock_minimo;
    }

    public function setStockMinimo(int $stock_minimo): self
    {
        $this->stock_minimo = $stock_minimo;

        return $this;
    }

    /**
     * @return Collection|AjustesStock[]
     */
    public function getAjustesStocks(): Collection
    {
        return $this->ajustesStocks;
    }

    public function addAjustesStock(AjustesStock $ajustesStock): self
    {
        if (!$this->ajustesStocks->contains($ajustesStock)) {
            $this->ajustesStocks[] = $ajustesStock;
            $ajustesStock->setProducto($this);
        }

        return $this;
    }

    public function removeAjustesStock(AjustesStock $ajustesStock): self
    {
        if ($this->ajustesStocks->contains($ajustesStock)) {
            $this->ajustesStocks->removeElement($ajustesStock);
            // set the owning side to null (unless already changed)
            if ($ajustesStock->getProducto() === $this) {
                $ajustesStock->setProducto(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->getTitulo();
    }

    public function getDiametro(): ?float
    {
        return $this->diametro;
    }

    public function setDiametro(?float $diametro): self
    {
        $this->diametro = $diametro;

        return $this;
    }

    public function getLargo(): ?float
    {
        return $this->largo;
    }

    public function setLargo(?float $largo): self
    {
        $this->largo = $largo;

        return $this;
    }

    public function getAncho(): ?float
    {
        return $this->ancho;
    }

    public function setAncho(?float $ancho): self
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(?string $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getUtilidad(): ?string
    {
        return $this->utilidad;
    }

    public function setUtilidad(?string $utilidad): self
    {
        $this->utilidad = $utilidad;

        return $this;
    }

    public function getPresentacion(): ?string
    {
        return $this->presentacion;
    }

    public function setPresentacion(?string $presentacion): self
    {
        $this->presentacion = $presentacion;

        return $this;
    }

    /**
     * @return Collection|CategoriasProductos[]
     */
    public function getCategoriasProductos(): Collection
    {
        return $this->categoriasProductos;
    }

    public function addCategoriasProducto(CategoriasProductos $categoriasProducto): self
    {
        if (!$this->categoriasProductos->contains($categoriasProducto)) {
            $this->categoriasProductos[] = $categoriasProducto;
            $categoriasProducto->setProducto($this);
        }

        return $this;
    }

    public function removeCategoriasProducto(CategoriasProductos $categoriasProducto): self
    {
        if ($this->categoriasProductos->contains($categoriasProducto)) {
            $this->categoriasProductos->removeElement($categoriasProducto);
            // set the owning side to null (unless already changed)
            if ($categoriasProducto->getProducto() === $this) {
                $categoriasProducto->setProducto(null);
            }
        }

        return $this;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

    public function setIva(?float $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getMaterialReparacion(): ?bool
    {
        return $this->materialReparacion;
    }

    public function setMaterialReparacion(?bool $materialReparacion): self
    {
        $this->materialReparacion = $materialReparacion;

        return $this;
    }

    /**
     * @return Collection|ReparacionesProductos[]
     */
    public function getReparacionesProductos(): Collection
    {
        return $this->reparacionesProductos;
    }

    public function addReparacionesProducto(ReparacionesProductos $reparacionesProducto): self
    {
        if (!$this->reparacionesProductos->contains($reparacionesProducto)) {
            $this->reparacionesProductos[] = $reparacionesProducto;
            $reparacionesProducto->setProducto($this);
        }

        return $this;
    }

    public function removeReparacionesProducto(ReparacionesProductos $reparacionesProducto): self
    {
        if ($this->reparacionesProductos->contains($reparacionesProducto)) {
            $this->reparacionesProductos->removeElement($reparacionesProducto);
            // set the owning side to null (unless already changed)
            if ($reparacionesProducto->getProducto() === $this) {
                $reparacionesProducto->setProducto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReglasPreciosProductos[]
     */
    public function getReglasPreciosProductos(): Collection
    {
        return $this->reglasPreciosProductos;
    }

    public function addReglasPreciosProducto(ReglasPreciosProductos $reglasPreciosProducto): self
    {
        if (!$this->reglasPreciosProductos->contains($reglasPreciosProducto)) {
            $this->reglasPreciosProductos[] = $reglasPreciosProducto;
            $reglasPreciosProducto->setProducto($this);
        }

        return $this;
    }

    public function removeReglasPreciosProducto(ReglasPreciosProductos $reglasPreciosProducto): self
    {
        if ($this->reglasPreciosProductos->contains($reglasPreciosProducto)) {
            $this->reglasPreciosProductos->removeElement($reglasPreciosProducto);
            // set the owning side to null (unless already changed)
            if ($reglasPreciosProducto->getProducto() === $this) {
                $reglasPreciosProducto->setProducto(null);
            }
        }

        return $this;
    }
}
