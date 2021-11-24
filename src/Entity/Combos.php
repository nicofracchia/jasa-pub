<?php

namespace App\Entity;

use App\Repository\CombosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CombosRepository::class)
 */
class Combos
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
     * @ORM\Column(type="float")
     */
    private $porcentaje_costo;

    /**
     * @ORM\Column(type="float")
     */
    private $precio_final;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $habilitado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminado = 0;

    /**
     * @ORM\OneToMany(targetEntity=CombosProductos::class, mappedBy="id_combo")
     */
    private $combosProductos;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="combos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $almacen;

    public function __construct()
    {
        $this->combosProductos = new ArrayCollection();
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

    public function getPorcentajeCosto(): ?float
    {
        return $this->porcentaje_costo;
    }

    public function setPorcentajeCosto(float $porcentaje_costo): self
    {
        $this->porcentaje_costo = $porcentaje_costo;

        return $this;
    }

    public function getPrecioFinal(): ?float
    {
        return $this->precio_final;
    }

    public function setPrecioFinal(float $precio_final): self
    {
        $this->precio_final = $precio_final;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

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
     * @return Collection|CombosProductos[]
     */
    public function getCombosProductos(): Collection
    {
        return $this->combosProductos;
    }

    public function addCombosProducto(CombosProductos $combosProducto): self
    {
        if (!$this->combosProductos->contains($combosProducto)) {
            $this->combosProductos[] = $combosProducto;
            $combosProducto->setIdCombo($this);
        }

        return $this;
    }

    public function removeCombosProducto(CombosProductos $combosProducto): self
    {
        if ($this->combosProductos->contains($combosProducto)) {
            $this->combosProductos->removeElement($combosProducto);
            // set the owning side to null (unless already changed)
            if ($combosProducto->getIdCombo() === $this) {
                $combosProducto->setIdCombo(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->getNombre();
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
}
