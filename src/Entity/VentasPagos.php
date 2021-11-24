<?php

namespace App\Entity;

use App\Repository\VentasPagosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VentasPagosRepository::class)
 */
class VentasPagos
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
     * @ORM\Column(type="float")
     */
    private $monto;

    /**
     * @ORM\ManyToOne(targetEntity=MediosPago::class, inversedBy="ventasPagos")
     * @ORM\JoinColumn(nullable=false)
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
     * @ORM\ManyToOne(targetEntity=Ventas::class, inversedBy="ventasPagos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $venta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\Column(type="float")
     */
    private $interes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notaVenta;

    /**
     * @ORM\OneToMany(targetEntity=VentasPagosDetalle::class, mappedBy="pago")
     */
    private $ventasPagosDetalles;

    public function __construct()
    {
        $this->ventasPagosDetalles = new ArrayCollection();
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

    public function getMonto(): ?float
    {
        return $this->monto;
    }

    public function setMonto(float $monto): self
    {
        $this->monto = $monto;

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

    public function getVenta(): ?Ventas
    {
        return $this->venta;
    }

    public function setVenta(?Ventas $venta): self
    {
        $this->venta = $venta;

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

    public function setInteres(float $interes): self
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

    /**
     * @return Collection|VentasPagosDetalle[]
     */
    public function getVentasPagosDetalles(): Collection
    {
        return $this->ventasPagosDetalles;
    }

    public function addVentasPagosDetalle(VentasPagosDetalle $ventasPagosDetalle): self
    {
        if (!$this->ventasPagosDetalles->contains($ventasPagosDetalle)) {
            $this->ventasPagosDetalles[] = $ventasPagosDetalle;
            $ventasPagosDetalle->setPago($this);
        }

        return $this;
    }

    public function removeVentasPagosDetalle(VentasPagosDetalle $ventasPagosDetalle): self
    {
        if ($this->ventasPagosDetalles->contains($ventasPagosDetalle)) {
            $this->ventasPagosDetalles->removeElement($ventasPagosDetalle);
            // set the owning side to null (unless already changed)
            if ($ventasPagosDetalle->getPago() === $this) {
                $ventasPagosDetalle->setPago(null);
            }
        }

        return $this;
    }
}
