<?php

namespace App\Entity;

use App\Repository\ReparacionesEstadosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparacionesEstadosRepository::class)
 */
class ReparacionesEstados
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reparaciones::class, inversedBy="reparacionesEstados")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reparacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

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

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }
}
