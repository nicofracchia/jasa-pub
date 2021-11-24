<?php

namespace App\Entity;

use App\Repository\MovimientosAlmacenesRecepcionesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovimientosAlmacenesRecepcionesRepository::class)
 */
class MovimientosAlmacenesRecepciones
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="movimientosAlmacenesRecepciones")
     */
    private $producto;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\ManyToOne(targetEntity=MovimientosAlmacenes::class, inversedBy="movimientosAlmacenesRecepciones")
     */
    private $movimiento;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMovimiento(): ?MovimientosAlmacenes
    {
        return $this->movimiento;
    }

    public function setMovimiento(?MovimientosAlmacenes $movimiento): self
    {
        $this->movimiento = $movimiento;

        return $this;
    }
}
