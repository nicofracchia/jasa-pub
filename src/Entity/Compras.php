<?php

namespace App\Entity;

use App\Repository\ComprasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComprasRepository::class)
 */
class Compras
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
     * @ORM\ManyToOne(targetEntity=Proveedores::class, inversedBy="compras")
     */
    private $proveedor;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="compras")
     */
    private $almacen;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $precio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity=ComprasProductos::class, mappedBy="compra")
     */
    private $comprasProductos;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $recepcion;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="compras")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creador;

    public function __construct()
    {
        $this->comprasProductos = new ArrayCollection();
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

    public function getProveedor(): ?Proveedores
    {
        return $this->proveedor;
    }

    public function setProveedor(?Proveedores $proveedor): self
    {
        $this->proveedor = $proveedor;

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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(?float $precio): self
    {
        $this->precio = $precio;

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
     * @return Collection|ComprasProductos[]
     */
    public function getComprasProductos(): Collection
    {
        return $this->comprasProductos;
    }

    public function addComprasProducto(ComprasProductos $comprasProducto): self
    {
        if (!$this->comprasProductos->contains($comprasProducto)) {
            $this->comprasProductos[] = $comprasProducto;
            $comprasProducto->setCompra($this);
        }

        return $this;
    }

    public function removeComprasProducto(ComprasProductos $comprasProducto): self
    {
        if ($this->comprasProductos->contains($comprasProducto)) {
            $this->comprasProductos->removeElement($comprasProducto);
            // set the owning side to null (unless already changed)
            if ($comprasProducto->getCompra() === $this) {
                $comprasProducto->setCompra(null);
            }
        }

        return $this;
    }

    public function getRecepcion(): ?\DateTimeInterface
    {
        return $this->recepcion;
    }

    public function setRecepcion(?\DateTimeInterface $recepcion): self
    {
        $this->recepcion = $recepcion;

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
}
