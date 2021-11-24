<?php

namespace App\Entity;

use App\Repository\VentasEnviosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VentasEnviosRepository::class)
 */
class VentasEnvios
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Ventas::class, inversedBy="ventasEnvios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $venta;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $costoEnvio;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $costoEmbalaje;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codigoSeguimiento;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCostoEnvio(): ?float
    {
        return $this->costoEnvio;
    }

    public function setCostoEnvio(?float $costoEnvio): self
    {
        $this->costoEnvio = $costoEnvio;

        return $this;
    }

    public function getCostoEmbalaje(): ?float
    {
        return $this->costoEmbalaje;
    }

    public function setCostoEmbalaje(?float $costoEmbalaje): self
    {
        $this->costoEmbalaje = $costoEmbalaje;

        return $this;
    }

    public function getCodigoSeguimiento(): ?string
    {
        return $this->codigoSeguimiento;
    }

    public function setCodigoSeguimiento(?string $codigoSeguimiento): self
    {
        $this->codigoSeguimiento = $codigoSeguimiento;

        return $this;
    }
}
