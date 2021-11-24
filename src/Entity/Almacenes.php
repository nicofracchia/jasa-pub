<?php

namespace App\Entity;

use App\Repository\AlmacenesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlmacenesRepository::class)
 */
class Almacenes
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminado = 0;

    /**
     * @ORM\OneToMany(targetEntity=Usuarios::class, mappedBy="almacen")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity=ClientesAlmacenes::class, mappedBy="id_almacen")
     */
    private $clientesAlmacenes;

    /**
     * @ORM\OneToMany(targetEntity=ProveedoresAlmacenes::class, mappedBy="id_almacen")
     */
    private $proveedoresAlmacenes;

    /**
     * @ORM\OneToMany(targetEntity=ProductosAlmacenes::class, mappedBy="id_almacen")
     */
    private $productosAlmacenes;

    /**
     * @ORM\OneToMany(targetEntity=Compras::class, mappedBy="almacen")
     */
    private $compras;

    /**
     * @ORM\OneToMany(targetEntity=AjustesStock::class, mappedBy="almacen")
     */
    private $ajustesStocks;

    /**
     * @ORM\OneToMany(targetEntity=Cotizaciones::class, mappedBy="almacen")
     */
    private $cotizaciones;

    /**
     * @ORM\OneToMany(targetEntity=Ventas::class, mappedBy="almacen")
     */
    private $ventas;

    /**
     * @ORM\OneToMany(targetEntity=Reparaciones::class, mappedBy="almacen")
     */
    private $reparaciones;

    /**
     * @ORM\OneToMany(targetEntity=Combos::class, mappedBy="almacen")
     */
    private $combos;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenes::class, mappedBy="desde")
     */
    private $movimientosAlmacenesDesde;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenes::class, mappedBy="hacia")
     */
    private $movimientosAlmacenesHacia;

    public function __construct()
    {
        $this->clientesAlmacenes = new ArrayCollection();
        $this->proveedoresAlmacenes = new ArrayCollection();
        $this->productosAlmacenes = new ArrayCollection();
        $this->compras = new ArrayCollection();
        $this->ajustesStocks = new ArrayCollection();
        $this->cotizaciones = new ArrayCollection();
        $this->ventas = new ArrayCollection();
        $this->reparaciones = new ArrayCollection();
        $this->combos = new ArrayCollection();
        $this->movimientosAlmacenesDesde = new ArrayCollection();
        $this->movimientosAlmacenesHacia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

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

    public function getUsuarios(): ?Usuarios
    {
        return $this->usuarios;
    }

    public function setUsuarios(Usuarios $usuarios): self
    {
        $this->usuarios = $usuarios;

        // set the owning side of the relation if necessary
        if ($usuarios->getAlmacen() !== $this) {
            $usuarios->setAlmacen($this);
        }

        return $this;
    }

    /**
     * @return Collection|ClientesAlmacenes[]
     */
    public function getClientesAlmacenes(): Collection
    {
        return $this->clientesAlmacenes;
    }

    public function addClientesAlmacene(ClientesAlmacenes $clientesAlmacene): self
    {
        if (!$this->clientesAlmacenes->contains($clientesAlmacene)) {
            $this->clientesAlmacenes[] = $clientesAlmacene;
            $clientesAlmacene->setIdAlmacen($this);
        }

        return $this;
    }

    public function removeClientesAlmacene(ClientesAlmacenes $clientesAlmacene): self
    {
        if ($this->clientesAlmacenes->contains($clientesAlmacene)) {
            $this->clientesAlmacenes->removeElement($clientesAlmacene);
            // set the owning side to null (unless already changed)
            if ($clientesAlmacene->getIdAlmacen() === $this) {
                $clientesAlmacene->setIdAlmacen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProveedoresAlmacenes[]
     */
    public function getProveedoresAlmacenes(): Collection
    {
        return $this->proveedoresAlmacenes;
    }

    public function addProveedoresAlmacene(ProveedoresAlmacenes $proveedoresAlmacene): self
    {
        if (!$this->proveedoresAlmacenes->contains($proveedoresAlmacene)) {
            $this->proveedoresAlmacenes[] = $proveedoresAlmacene;
            $proveedoresAlmacene->setIdAlmacen($this);
        }

        return $this;
    }

    public function removeProveedoresAlmacene(ProveedoresAlmacenes $proveedoresAlmacene): self
    {
        if ($this->proveedoresAlmacenes->contains($proveedoresAlmacene)) {
            $this->proveedoresAlmacenes->removeElement($proveedoresAlmacene);
            // set the owning side to null (unless already changed)
            if ($proveedoresAlmacene->getIdAlmacen() === $this) {
                $proveedoresAlmacene->setIdAlmacen(null);
            }
        }

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
            $productosAlmacene->setIdAlmacen($this);
        }

        return $this;
    }

    public function removeProductosAlmacene(ProductosAlmacenes $productosAlmacene): self
    {
        if ($this->productosAlmacenes->contains($productosAlmacene)) {
            $this->productosAlmacenes->removeElement($productosAlmacene);
            // set the owning side to null (unless already changed)
            if ($productosAlmacene->getIdAlmacen() === $this) {
                $productosAlmacene->setIdAlmacen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compras[]
     */
    public function getCompras(): Collection
    {
        return $this->compras;
    }

    public function addCompra(Compras $compra): self
    {
        if (!$this->compras->contains($compra)) {
            $this->compras[] = $compra;
            $compra->setAlmacen($this);
        }

        return $this;
    }

    public function removeCompra(Compras $compra): self
    {
        if ($this->compras->contains($compra)) {
            $this->compras->removeElement($compra);
            // set the owning side to null (unless already changed)
            if ($compra->getAlmacen() === $this) {
                $compra->setAlmacen(null);
            }
        }

        return $this;
    }

    public function __toString() {
      return $this->getNombre();
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
            $ajustesStock->setAlmacen($this);
        }

        return $this;
    }

    public function removeAjustesStock(AjustesStock $ajustesStock): self
    {
        if ($this->ajustesStocks->contains($ajustesStock)) {
            $this->ajustesStocks->removeElement($ajustesStock);
            // set the owning side to null (unless already changed)
            if ($ajustesStock->getAlmacen() === $this) {
                $ajustesStock->setAlmacen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cotizaciones[]
     */
    public function getCotizaciones(): Collection
    {
        return $this->cotizaciones;
    }

    public function addCotizacione(Cotizaciones $cotizacione): self
    {
        if (!$this->cotizaciones->contains($cotizacione)) {
            $this->cotizaciones[] = $cotizacione;
            $cotizacione->setAlmacen($this);
        }

        return $this;
    }

    public function removeCotizacione(Cotizaciones $cotizacione): self
    {
        if ($this->cotizaciones->contains($cotizacione)) {
            $this->cotizaciones->removeElement($cotizacione);
            // set the owning side to null (unless already changed)
            if ($cotizacione->getAlmacen() === $this) {
                $cotizacione->setAlmacen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ventas[]
     */
    public function getVentas(): Collection
    {
        return $this->ventas;
    }

    public function addVenta(Ventas $venta): self
    {
        if (!$this->ventas->contains($venta)) {
            $this->ventas[] = $venta;
            $venta->setAlmacen($this);
        }

        return $this;
    }

    public function removeVenta(Ventas $venta): self
    {
        if ($this->ventas->contains($venta)) {
            $this->ventas->removeElement($venta);
            // set the owning side to null (unless already changed)
            if ($venta->getAlmacen() === $this) {
                $venta->setAlmacen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reparaciones[]
     */
    public function getReparaciones(): Collection
    {
        return $this->reparaciones;
    }

    public function addReparacione(Reparaciones $reparacione): self
    {
        if (!$this->reparaciones->contains($reparacione)) {
            $this->reparaciones[] = $reparacione;
            $reparacione->setAlmacen($this);
        }

        return $this;
    }

    public function removeReparacione(Reparaciones $reparacione): self
    {
        if ($this->reparaciones->contains($reparacione)) {
            $this->reparaciones->removeElement($reparacione);
            // set the owning side to null (unless already changed)
            if ($reparacione->getAlmacen() === $this) {
                $reparacione->setAlmacen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Combos[]
     */
    public function getCombos(): Collection
    {
        return $this->combos;
    }

    public function addCombo(Combos $combo): self
    {
        if (!$this->combos->contains($combo)) {
            $this->combos[] = $combo;
            $combo->setAlmacen($this);
        }

        return $this;
    }

    public function removeCombo(Combos $combo): self
    {
        if ($this->combos->contains($combo)) {
            $this->combos->removeElement($combo);
            // set the owning side to null (unless already changed)
            if ($combo->getAlmacen() === $this) {
                $combo->setAlmacen(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovimientosAlmacenes[]
     */
    public function getMovimientosAlmacenesDesde(): Collection
    {
        return $this->movimientosAlmacenesDesde;
    }

    public function addMovimientosAlmacenesDesde(MovimientosAlmacenes $movimientosAlmacenesDesde): self
    {
        if (!$this->movimientosAlmacenesDesde->contains($movimientosAlmacenesDesde)) {
            $this->movimientosAlmacenesDesde[] = $movimientosAlmacenesDesde;
            $movimientosAlmacenesDesde->setDesde($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesDesde(MovimientosAlmacenes $movimientosAlmacenesDesde): self
    {
        if ($this->movimientosAlmacenesDesde->contains($movimientosAlmacenesDesde)) {
            $this->movimientosAlmacenesDesde->removeElement($movimientosAlmacenesDesde);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesDesde->getDesde() === $this) {
                $movimientosAlmacenesDesde->setDesde(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovimientosAlmacenes[]
     */
    public function getMovimientosAlmacenesHacia(): Collection
    {
        return $this->movimientosAlmacenesHacia;
    }

    public function addMovimientosAlmacenesHacium(MovimientosAlmacenes $movimientosAlmacenesHacium): self
    {
        if (!$this->movimientosAlmacenesHacia->contains($movimientosAlmacenesHacium)) {
            $this->movimientosAlmacenesHacia[] = $movimientosAlmacenesHacium;
            $movimientosAlmacenesHacium->setHacia($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesHacium(MovimientosAlmacenes $movimientosAlmacenesHacium): self
    {
        if ($this->movimientosAlmacenesHacia->contains($movimientosAlmacenesHacium)) {
            $this->movimientosAlmacenesHacia->removeElement($movimientosAlmacenesHacium);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesHacium->getHacia() === $this) {
                $movimientosAlmacenesHacium->setHacia(null);
            }
        }

        return $this;
    }
}
