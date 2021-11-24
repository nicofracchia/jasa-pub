<?php

namespace App\Entity;

use App\Repository\CategoriasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriasRepository::class)
 */
class Categorias
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
     * @ORM\Column(type="integer")
     */
    private $id_padre;

    /**
     * @ORM\Column(type="integer")
     */
    private $final;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $grupoId;

    /**
     * @ORM\Column(type="smallint")
     */
    private $habilitado;

    /**
     * @ORM\Column(type="smallint")
     */
    private $eliminado;

    /**
     * @ORM\OneToMany(targetEntity=CategoriasProductos::class, mappedBy="categoria")
     */
    private $categoriasProductos;

    public function __construct()
    {
        $this->categoriasProductos = new ArrayCollection();
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

    public function getIdPadre(): ?int
    {
        return $this->id_padre;
    }

    public function setIdPadre(int $id_padre): self
    {
        $this->id_padre = $id_padre;

        return $this;
    }

    public function getFinal(): ?int
    {
        return $this->final;
    }

    public function setFinal(int $final): self
    {
        $this->final = $final;

        return $this;
    }

    public function __toString() {
        return $this->getNombre();
    }

    public function getGrupoId(): ?string
    {
        return $this->grupoId;
    }

    public function setGrupoId(string $grupoId): self
    {
        $this->grupoId = $grupoId;

        return $this;
    }

    public function getHabilitado(): ?int
    {
        return $this->habilitado;
    }

    public function setHabilitado(int $habilitado): self
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    public function getEliminado(): ?int
    {
        return $this->eliminado;
    }

    public function setEliminado(int $eliminado): self
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * @return Collection|CategoriasProductos[]
     */
    public function getCategoriasProductos(): Collection
    {
        return $this->categoriasProductos;
    }

    public function addCategoriasProducto(CategoriasProductos $categoriasProducto): self
    {
        if (!$this->categoriasProductos->contains($categoriasProducto)) {
            $this->categoriasProductos[] = $categoriasProducto;
            $categoriasProducto->setCategoria($this);
        }

        return $this;
    }

    public function removeCategoriasProducto(CategoriasProductos $categoriasProducto): self
    {
        if ($this->categoriasProductos->contains($categoriasProducto)) {
            $this->categoriasProductos->removeElement($categoriasProducto);
            // set the owning side to null (unless already changed)
            if ($categoriasProducto->getCategoria() === $this) {
                $categoriasProducto->setCategoria(null);
            }
        }

        return $this;
    }
}
