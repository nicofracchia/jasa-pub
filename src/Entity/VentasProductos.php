<?php

namespace App\Entity;

use App\Repository\VentasProductosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VentasProductosRepository::class)
 */
class VentasProductos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ventas::class, inversedBy="ventasProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $venta;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="ventasProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto;

    /**
     * @ORM\Column(type="integer")
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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProducto(): ?Productos
    {
        return $this->producto;
    }

    public function setProducto(?Productos $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
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
}
