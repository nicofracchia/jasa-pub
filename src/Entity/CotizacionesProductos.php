<?php

namespace App\Entity;

use App\Repository\CotizacionesProductosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CotizacionesProductosRepository::class)
 */
class CotizacionesProductos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cotizaciones::class, inversedBy="cotizacionesProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_cotizacion;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="cotizacionesProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_producto;

    /**
     * @ORM\Column(type="float")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float")
     */
    private $costo;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reservado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCotizacion(): ?Cotizaciones
    {
        return $this->id_cotizacion;
    }

    public function setIdCotizacion(?Cotizaciones $id_cotizacion): self
    {
        $this->id_cotizacion = $id_cotizacion;

        return $this;
    }

    public function getIdProducto(): ?Productos
    {
        return $this->id_producto;
    }

    public function setIdProducto(?Productos $id_producto): self
    {
        $this->id_producto = $id_producto;

        return $this;
    }

    public function getCantidad(): ?float
    {
        return $this->cantidad;
    }

    public function setCantidad(float $cantidad): self
    {
        $this->cantidad = $cantidad;

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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getReservado(): ?bool
    {
        return $this->reservado;
    }

    public function setReservado(bool $reservado): self
    {
        $this->reservado = $reservado;

        return $this;
    }
}