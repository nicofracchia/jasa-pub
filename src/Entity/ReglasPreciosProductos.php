<?php

namespace App\Entity;

use App\Repository\ReglasPreciosProductosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReglasPreciosProductosRepository::class)
 */
class ReglasPreciosProductos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="reglasPreciosProductos")
     */
    private $producto;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

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
}
