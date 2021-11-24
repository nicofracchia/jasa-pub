<?php

namespace App\Entity;

use App\Repository\MovimientosCajaDiariaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovimientosCajaDiariaRepository::class)
 */
class MovimientosCajaDiaria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $movimiento;

    /**
     * @ORM\Column(type="smallint")
     */
    private $tipo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
