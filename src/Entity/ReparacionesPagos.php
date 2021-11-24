<?php

namespace App\Entity;

use App\Repository\ReparacionesPagosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparacionesPagosRepository::class)
 */
class ReparacionesPagos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reparaciones::class, inversedBy="reparacionesPagos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reparacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="float")
     */
    private $monto;

    /**
     * @ORM\OneToMany(targetEntity=ReparacionesPagosDetalle::class, mappedBy="pago")
     */
    private $reparacionesPagosDetalles;

    /**
     * @ORM\ManyToOne(targetEntity=MediosPago::class, inversedBy="reparacionesPagos")
     */
    private $medio_pago;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comprobante;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url_comprobante;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $interes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notaVenta;

    public function __construct()
    {
        $this->reparacionesPagosDetalles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReparacion(): ?Reparaciones
    {
        return $this->reparacion;
    }

    public function setReparacion(?Reparaciones $reparacion): self
    {
        $this->reparacion = $reparacion;

        return $this;
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

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(float $monto): self
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * @return Collection|ReparacionesPagosDetalle[]
     */
    public function getReparacionesPagosDetalles(): Collection
    {
        return $this->reparacionesPagosDetalles;
    }

    public function addReparacionesPagosDetalle(ReparacionesPagosDetalle $reparacionesPagosDetalle): self
    {
        if (!$this->reparacionesPagosDetalles->contains($reparacionesPagosDetalle)) {
            $this->reparacionesPagosDetalles[] = $reparacionesPagosDetalle;
            $reparacionesPagosDetalle->setPago($this);
        }

        return $this;
    }

    public function removeReparacionesPagosDetalle(ReparacionesPagosDetalle $reparacionesPagosDetalle): self
    {
        if ($this->reparacionesPagosDetalles->contains($reparacionesPagosDetalle)) {
            $this->reparacionesPagosDetalles->removeElement($reparacionesPagosDetalle);
            // set the owning side to null (unless already changed)
            if ($reparacionesPagosDetalle->getPago() === $this) {
                $reparacionesPagosDetalle->setPago(null);
            }
        }

        return $this;
    }

    public function getMedioPago(): ?MediosPago
    {
        return $this->medio_pago;
    }

    public function setMedioPago(?MediosPago $medio_pago): self
    {
        $this->medio_pago = $medio_pago;

        return $this;
    }

    public function getComprobante(): ?string
    {
        return $this->comprobante;
    }

    public function setComprobante(?string $comprobante): self
    {
        $this->comprobante = $comprobante;

        return $this;
    }

    public function getUrlComprobante(): ?string
    {
        return $this->url_comprobante;
    }

    public function setUrlComprobante(?string $url_comprobante): self
    {
        $this->url_comprobante = $url_comprobante;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getInteres(): ?float
    {
        return $this->interes;
    }

    public function setInteres(?float $interes): self
    {
        $this->interes = $interes;

        return $this;
    }

    public function getNotaVenta(): ?bool
    {
        return $this->notaVenta;
    }

    public function setNotaVenta(bool $notaVenta): self
    {
        $this->notaVenta = $notaVenta;

        return $this;
    }
}
