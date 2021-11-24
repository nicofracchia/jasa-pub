<?php

namespace App\Entity;

use App\Repository\MovimientosCajaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovimientosCajaRepository::class)
 */
class MovimientosCaja
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Caja::class, inversedBy="movimientosCajas")
     */
    private $caja;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $movimiento;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tipoMovimiento;

    /**
     * @ORM\Column(type="float")
     */
    private $monto;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="movimientosCajas")
     */
    private $creador;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaja(): ?Caja
    {
        return $this->caja;
    }

    public function setCaja(?Caja $caja): self
    {
        $this->caja = $caja;

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

    public function getMovimiento(): ?string
    {
        return $this->movimiento;
    }

    public function setMovimiento(string $movimiento): self
    {
        $this->movimiento = $movimiento;

        return $this;
    }

    public function getTipoMovimiento(): ?bool
    {
        return $this->tipoMovimiento;
    }

    public function setTipoMovimiento(bool $tipoMovimiento): self
    {
        $this->tipoMovimiento = $tipoMovimiento;

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

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

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
