<?php

namespace App\Entity;

use App\Repository\UnidadesMedidaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UnidadesMedidaRepository::class)
 */
class UnidadesMedida
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
     * @ORM\Column(type="string", length=255)
     */
    private $nombre_padre;

    /**
     * @ORM\OneToMany(targetEntity=VentasProductos::class, mappedBy="unidad_medida")
     */
    private $ventasProductos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $corto;

    public function __construct()
    {
        $this->ventasProductos = new ArrayCollection();
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

    public function getNombrePadre(): ?string
    {
        return $this->nombre_padre;
    }

    public function setNombrePadre(string $nombre_padre): self
    {
        $this->nombre_padre = $nombre_padre;

        return $this;
    }

    /**
     * @return Collection|VentasProductos[]
     */
    public function getVentasProductos(): Collection
    {
        return $this->ventasProductos;
    }

    public function addVentasProducto(VentasProductos $ventasProducto): self
    {
        if (!$this->ventasProductos->contains($ventasProducto)) {
            $this->ventasProductos[] = $ventasProducto;
            $ventasProducto->setUnidadMedida($this);
        }

        return $this;
    }

    public function removeVentasProducto(VentasProductos $ventasProducto): self
    {
        if ($this->ventasProductos->contains($ventasProducto)) {
            $this->ventasProductos->removeElement($ventasProducto);
            // set the owning side to null (unless already changed)
            if ($ventasProducto->getUnidadMedida() === $this) {
                $ventasProducto->setUnidadMedida(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->getNombre();
    }

    public function getCorto(): ?string
    {
        return $this->corto;
    }

    public function setCorto(?string $corto): self
    {
        $this->corto = $corto;

        return $this;
    }
}
