<?php

namespace App\Entity;

use App\Repository\ProveedoresProductosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProveedoresProductosRepository::class)
 */
class ProveedoresProductos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Proveedores::class, inversedBy="proveedoresProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_proveedor;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="proveedoresProductos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_producto;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $costo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProveedor(): ?Proveedores
    {
        return $this->id_proveedor;
    }

    public function setIdProveedor(?Proveedores $id_proveedor): self
    {
        $this->id_proveedor = $id_proveedor;

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
