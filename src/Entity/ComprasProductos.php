<?php

namespace App\Entity;

use App\Repository\ComprasProductosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComprasProductosRepository::class)
 */
class ComprasProductos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Compras::class, inversedBy="comprasProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compra;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="comprasProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompra(): ?Compras
    {
        return $this->compra;
    }

    public function setCompra(?Compras $compra): self
    {
        $this->compra = $compra;

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
}
