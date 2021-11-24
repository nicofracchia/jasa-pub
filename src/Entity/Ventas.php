<?php

namespace App\Entity;

use App\Repository\VentasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VentasRepository::class)
 */
class Ventas
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
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity=VentasProductos::class, mappedBy="venta")
     */
    private $ventasProductos;

    /**
     * @ORM\Column(type="float")
     */
    private $precio_final;

    /**
     * @ORM\OneToMany(targetEntity=VentasPagos::class, mappedBy="venta")
     */
    private $ventasPagos;

    /**
     * @ORM\ManyToOne(targetEntity=Clientes::class, inversedBy="ventas")
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="ventas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $almacen;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="ventas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creador;

    /**
     * @ORM\OneToMany(targetEntity=VentasEnvios::class, mappedBy="venta")
     */
    private $ventasEnvios;

    /**
     * @ORM\OneToMany(targetEntity=Cotizaciones::class, mappedBy="venta")
     */
    private $cotizaciones;

    public function __construct()
    {
        $this->ventasProductos = new ArrayCollection();
        $this->ventasPagos = new ArrayCollection();
        $this->ventasEnvios = new ArrayCollection();
        $this->cotizaciones = new ArrayCollection();
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
            $ventasProducto->setVenta($this);
        }

        return $this;
    }

    public function removeVentasProducto(VentasProductos $ventasProducto): self
    {
        if ($this->ventasProductos->contains($ventasProducto)) {
            $this->ventasProductos->removeElement($ventasProducto);
            // set the owning side to null (unless already changed)
            if ($ventasProducto->getVenta() === $this) {
                $ventasProducto->setVenta(null);
            }
        }

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

    /**
     * @return Collection|VentasPagos[]
     */
    public function getVentasPagos(): Collection
    {
        return $this->ventasPagos;
    }

    public function addVentasPago(VentasPagos $ventasPago): self
    {
        if (!$this->ventasPagos->contains($ventasPago)) {
            $this->ventasPagos[] = $ventasPago;
            $ventasPago->setVenta($this);
        }

        return $this;
    }

    public function removeVentasPago(VentasPagos $ventasPago): self
    {
        if ($this->ventasPagos->contains($ventasPago)) {
            $this->ventasPagos->removeElement($ventasPago);
            // set the owning side to null (unless already changed)
            if ($ventasPago->getVenta() === $this) {
                $ventasPago->setVenta(null);
            }
        }

        return $this;
    }

    public function getCliente(): ?Clientes
    {
        return $this->cliente;
    }

    public function setCliente(?Clientes $cliente): self
    {
        $this->cliente = $cliente;

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

    public function getCreador(): ?Usuarios
    {
        return $this->creador;
    }

    public function setCreador(?Usuarios $creador): self
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * @return Collection|VentasEnvios[]
     */
    public function getVentasEnvios(): Collection
    {
        return $this->ventasEnvios;
    }

    public function addVentasEnvio(VentasEnvios $ventasEnvio): self
    {
        if (!$this->ventasEnvios->contains($ventasEnvio)) {
            $this->ventasEnvios[] = $ventasEnvio;
            $ventasEnvio->setVenta($this);
        }

        return $this;
    }

    public function removeVentasEnvio(VentasEnvios $ventasEnvio): self
    {
        if ($this->ventasEnvios->contains($ventasEnvio)) {
            $this->ventasEnvios->removeElement($ventasEnvio);
            // set the owning side to null (unless already changed)
            if ($ventasEnvio->getVenta() === $this) {
                $ventasEnvio->setVenta(null);
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
            $cotizacione->setVenta($this);
        }

        return $this;
    }

    public function removeCotizacione(Cotizaciones $cotizacione): self
    {
        if ($this->cotizaciones->contains($cotizacione)) {
            $this->cotizaciones->removeElement($cotizacione);
            // set the owning side to null (unless already changed)
            if ($cotizacione->getVenta() === $this) {
                $cotizacione->setVenta(null);
            }
        }

        return $this;
    }
}
