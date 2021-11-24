<?php

namespace App\Entity;

use App\Repository\ProveedoresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProveedoresRepository::class)
 */
class Proveedores
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notas;

    /**
     * @ORM\OneToMany(targetEntity=ProveedoresAlmacenes::class, mappedBy="id_proveedor")
     */
    private $proveedoresAlmacenes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $habilitado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminado = 0;

    /**
     * @ORM\OneToMany(targetEntity=ProveedoresProductos::class, mappedBy="id_proveedor")
     */
    private $proveedoresProductos;

    /**
     * @ORM\OneToMany(targetEntity=Compras::class, mappedBy="proveedor")
     */
    private $compras;

    public function __construct()
    {
        $this->proveedoresAlmacenes = new ArrayCollection();
        $this->proveedoresProductos = new ArrayCollection();
        $this->compras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCuit(): ?string
    {
        return $this->cuit;
    }

    public function setCuit(?string $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getNotas(): ?string
    {
        return $this->notas;
    }

    public function setNotas(?string $notas): self
    {
        $this->notas = $notas;

        return $this;
    }

    /**
     * @return Collection|ProveedoresAlmacenes[]
     */
    public function getProveedoresAlmacenes(): Collection
    {
        return $this->proveedoresAlmacenes;
    }

    public function addProveedoresAlmacene(ProveedoresAlmacenes $proveedoresAlmacene): self
    {
        if (!$this->proveedoresAlmacenes->contains($proveedoresAlmacene)) {
            $this->proveedoresAlmacenes[] = $proveedoresAlmacene;
            $proveedoresAlmacene->setIdProveedor($this);
        }

        return $this;
    }

    public function removeProveedoresAlmacene(ProveedoresAlmacenes $proveedoresAlmacene): self
    {
        if ($this->proveedoresAlmacenes->contains($proveedoresAlmacene)) {
            $this->proveedoresAlmacenes->removeElement($proveedoresAlmacene);
            // set the owning side to null (unless already changed)
            if ($proveedoresAlmacene->getIdProveedor() === $this) {
                $proveedoresAlmacene->setIdProveedor(null);
            }
        }

        return $this;
    }

    public function getHabilitado(): ?bool
    {
        return $this->habilitado;
    }

    public function setHabilitado(bool $habilitado): self
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    public function getEliminado(): ?bool
    {
        return $this->eliminado;
    }

    public function setEliminado(bool $eliminado): self
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * @return Collection|ProveedoresProductos[]
     */
    public function getProveedoresProductos(): Collection
    {
        return $this->proveedoresProductos;
    }

    public function addProveedoresProducto(ProveedoresProductos $proveedoresProducto): self
    {
        if (!$this->proveedoresProductos->contains($proveedoresProducto)) {
            $this->proveedoresProductos[] = $proveedoresProducto;
            $proveedoresProducto->setIdProveedor($this);
        }

        return $this;
    }

    public function removeProveedoresProducto(ProveedoresProductos $proveedoresProducto): self
    {
        if ($this->proveedoresProductos->contains($proveedoresProducto)) {
            $this->proveedoresProductos->removeElement($proveedoresProducto);
            // set the owning side to null (unless already changed)
            if ($proveedoresProducto->getIdProveedor() === $this) {
                $proveedoresProducto->setIdProveedor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compras[]
     */
    public function getCompras(): Collection
    {
        return $this->compras;
    }

    public function addCompra(Compras $compra): self
    {
        if (!$this->compras->contains($compra)) {
            $this->compras[] = $compra;
            $compra->setProveedor($this);
        }

        return $this;
    }

    public function removeCompra(Compras $compra): self
    {
        if ($this->compras->contains($compra)) {
            $this->compras->removeElement($compra);
            // set the owning side to null (unless already changed)
            if ($compra->getProveedor() === $this) {
                $compra->setProveedor(null);
            }
        }

        return $this;
    }
}
