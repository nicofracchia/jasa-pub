<?php

namespace App\Entity;

use App\Repository\ReparacionesProductosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparacionesProductosRepository::class)
 */
class ReparacionesProductos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reparaciones::class, inversedBy="reparacionesProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reparacion;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="reparacionesProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $precio;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $costo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReparacion(): ?Reparaciones
    {
        return $this->reparacion;
    }

    public function setReparacion(?Reparaciones $reparacion): self
    {
        $this->reparacion = $reparacion;

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

    public function getCantidad(): ?float
    {
        return $this->cantidad;
    }

    public function setCantidad(?float $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(?float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getCosto(): ?float
    {
        return $this->costo;
    }

    public function setCosto(?float $costo): self
    {
        $this->costo = $costo;

        return $this;
    }
}
