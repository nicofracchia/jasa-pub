<?php

namespace App\Entity;

use App\Repository\ProveedoresAlmacenesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProveedoresAlmacenesRepository::class)
 */
class ProveedoresAlmacenes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Proveedores::class, inversedBy="proveedoresAlmacenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_proveedor;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="proveedoresAlmacenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_almacen;

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

    public function getIdAlmacen(): ?Almacenes
    {
        return $this->id_almacen;
    }

    public function setIdAlmacen(?Almacenes $id_almacen): self
    {
        $this->id_almacen = $id_almacen;

        return $this;
    }
}
