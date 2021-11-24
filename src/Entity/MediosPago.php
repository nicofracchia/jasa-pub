<?php

namespace App\Entity;

use App\Repository\MediosPagoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediosPagoRepository::class)
 */
class MediosPago
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
     * @ORM\Column(type="smallint")
     */
    private $habilitado;

    /**
     * @ORM\Column(type="smallint")
     */
    private $eliminado;

    /**
     * @ORM\OneToMany(targetEntity=VentasPagos::class, mappedBy="medio_pago")
     */
    private $ventasPagos;

    /**
     * @ORM\OneToMany(targetEntity=ReparacionesPagos::class, mappedBy="medioPago")
     */
    private $reparacionesPagos;

    public function __construct()
    {
        $this->ventasPagos = new ArrayCollection();
        $this->reparacionesPagos = new ArrayCollection();
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
     * @return Collection|VentasPagos[]
     */
    public function getVentasPagos(): Collection
    {
        return $this->ventasPagos;
    }

    public function addVentasPago(VentasPagos $ventasPago): self
    {
        if (!$this->ventasPagos->contains($ventasPago)) {
            $this->ventasPagos[] = $ventasPago;
            $ventasPago->setMedioPago($this);
        }

        return $this;
    }

    public function removeVentasPago(VentasPagos $ventasPago): self
    {
        if ($this->ventasPagos->contains($ventasPago)) {
            $this->ventasPagos->removeElement($ventasPago);
            // set the owning side to null (unless already changed)
            if ($ventasPago->getMedioPago() === $this) {
                $ventasPago->setMedioPago(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReparacionesPagos[]
     */
    public function getReparacionesPagos(): Collection
    {
        return $this->reparacionesPagos;
    }

    public function addReparacionesPago(ReparacionesPagos $reparacionesPago): self
    {
        if (!$this->reparacionesPagos->contains($reparacionesPago)) {
            $this->reparacionesPagos[] = $reparacionesPago;
            $reparacionesPago->setMedioPago($this);
        }

        return $this;
    }

    public function removeReparacionesPago(ReparacionesPagos $reparacionesPago): self
    {
        if ($this->reparacionesPagos->contains($reparacionesPago)) {
            $this->reparacionesPagos->removeElement($reparacionesPago);
            // set the owning side to null (unless already changed)
            if ($reparacionesPago->getMedioPago() === $this) {
                $reparacionesPago->setMedioPago(null);
            }
        }

        return $this;
    }
}
