<?php

namespace App\Entity;

use App\Repository\CombosProductosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CombosProductosRepository::class)
 */
class CombosProductos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="combosProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_producto;

    /**
     * @ORM\ManyToOne(targetEntity=Combos::class, inversedBy="combosProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_combo;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdCombo(): ?Combos
    {
        return $this->id_combo;
    }

    public function setIdCombo(?Combos $id_combo): self
    {
        $this->id_combo = $id_combo;

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
