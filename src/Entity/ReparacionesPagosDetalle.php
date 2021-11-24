<?php

namespace App\Entity;

use App\Repository\ReparacionesPagosDetalleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparacionesPagosDetalleRepository::class)
 */
class ReparacionesPagosDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ReparacionesPagos::class, inversedBy="reparacionesPagosDetalles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pago;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPago(): ?ReparacionesPagos
    {
        return $this->pago;
    }

    public function setPago(?ReparacionesPagos $pago): self
    {
        $this->pago = $pago;

        return $this;
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

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }
}
