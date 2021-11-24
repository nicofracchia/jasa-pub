<?php

namespace App\Entity;

use App\Repository\CotizacionesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CotizacionesRepository::class)
 */
class Cotizaciones
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=Clientes::class, inversedBy="cotizaciones")
     * @ORM\JoinColumn(nullable=true)
     */
    private $id_cliente;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity=CotizacionesProductos::class, mappedBy="id_cotizacion")
     */
    private $cotizacionesProductos;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $hasta;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $descuento;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="cotizaciones")
     */
    private $creador;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mantenerPrecio;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="cotizaciones")
     */
    private $almacen;

    /**
     * @ORM\ManyToOne(targetEntity=Ventas::class, inversedBy="cotizaciones")
     */
    private $venta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    public function __construct()
    {
        $this->cotizacionesProductos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getIdCliente(): ?Clientes
    {
        return $this->id_cliente;
    }

    public function setIdCliente(?Clientes $id_cliente): self
    {
        $this->id_cliente = $id_cliente;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

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
            $cotizacionesProducto->setIdCotizacion($this);
        }

        return $this;
    }

    public function removeCotizacionesProducto(CotizacionesProductos $cotizacionesProducto): self
    {
        if ($this->cotizacionesProductos->contains($cotizacionesProducto)) {
            $this->cotizacionesProductos->removeElement($cotizacionesProducto);
            // set the owning side to null (unless already changed)
            if ($cotizacionesProducto->getIdCotizacion() === $this) {
                $cotizacionesProducto->setIdCotizacion(null);
            }
        }

        return $this;
    }

    public function getHasta(): ?\DateTimeInterface
    {
        return $this->hasta;
    }

    public function setHasta(?\DateTimeInterface $hasta): self
    {
        $this->hasta = $hasta;

        return $this;
    }

    public function getDescuento(): ?float
    {
        return $this->descuento;
    }

    public function setDescuento(?float $descuento): self
    {
        $this->descuento = $descuento;

        return $this;
    }

    public function getCreador(): ?Usuarios
    {
        return $this->creador;
    }

    public function setCreador(?Usuarios $creador): self
    {
        $this->creador = $creador;

        return $this;
    }

    public function getMantenerPrecio(): ?bool
    {
        return $this->mantenerPrecio;
    }

    public function setMantenerPrecio(bool $mantenerPrecio): self
    {
        $this->mantenerPrecio = $mantenerPrecio;

        return $this;
    }

    public function getAlmacen(): ?Almacenes
    {
        return $this->almacen;
    }

    public function setAlmacen(?Almacenes $almacen): self
    {
        $this->almacen = $almacen;

        return $this;
    }

    public function getVenta(): ?Ventas
    {
        return $this->venta;
    }

    public function setVenta(?Ventas $venta): self
    {
        $this->venta = $venta;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
