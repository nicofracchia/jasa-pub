<?php

namespace App\Entity;

use App\Repository\ReparacionesTareasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparacionesTareasRepository::class)
 */
class ReparacionesTareas
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
    private $tarea;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarea(): ?string
    {
        return $this->tarea;
    }

    public function setTarea(string $tarea): self
    {
        $this->tarea = $tarea;

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
}
