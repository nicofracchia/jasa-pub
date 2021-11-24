<?php

namespace App\Entity;

use App\Repository\ClientesAlmacenesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientesAlmacenesRepository::class)
 */
class ClientesAlmacenes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Clientes::class, inversedBy="clientesAlmacenes")
     */
    private $id_cliente;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="clientesAlmacenes")
     */
    private $id_almacen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCliente(): ?Clientes
    {
        return $this->id_cliente;
    }

    public function setIdCliente(?Clientes $id_cliente): self
    {
        $this->id_cliente = $id_cliente;

        return $this;
    }

    public function getIdAlmacen(): ?Almacenes
    {
        return $this->id_almacen;
    }

    public function setIdAlmacen(?Almacenes $id_almacen): self
    {
        $this->id_almacen = $id_almacen;

        return $this;
    }
}
