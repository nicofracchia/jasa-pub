<?php

namespace App\Entity;

use App\Repository\ClientesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientesRepository::class)
 */
class Clientes
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
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity=ClientesAlmacenes::class, mappedBy="id_cliente")
     */
    private $clientesAlmacenes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $habilitado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminado = 0;

    /**
     * @ORM\OneToMany(targetEntity=Cotizaciones::class, mappedBy="id_cliente")
     */
    private $cotizaciones;

    /**
     * @ORM\OneToMany(targetEntity=Ventas::class, mappedBy="cliente")
     */
    private $ventas;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $razonSocial;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cuentaCorriente;

    /**
     * @ORM\OneToMany(targetEntity=Reparaciones::class, mappedBy="cliente")
     */
    private $reparaciones;

    public function __construct()
    {
        $this->clientesAlmacenes = new ArrayCollection();
        $this->cotizaciones = new ArrayCollection();
        $this->ventas = new ArrayCollection();
        $this->reparaciones = new ArrayCollection();
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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * @return Collection|ClientesAlmacenes[]
     */
    public function getClientesAlmacenes(): Collection
    {
        return $this->clientesAlmacenes;
    }

    public function addClientesAlmacene(ClientesAlmacenes $clientesAlmacene): self
    {
        if (!$this->clientesAlmacenes->contains($clientesAlmacene)) {
            $this->clientesAlmacenes[] = $clientesAlmacene;
            $clientesAlmacene->setIdCliente($this);
        }

        return $this;
    }

    public function removeClientesAlmacene(ClientesAlmacenes $clientesAlmacene): self
    {
        if ($this->clientesAlmacenes->contains($clientesAlmacene)) {
            $this->clientesAlmacenes->removeElement($clientesAlmacene);
            // set the owning side to null (unless already changed)
            if ($clientesAlmacene->getIdCliente() === $this) {
                $clientesAlmacene->setIdCliente(null);
            }
        }

        return $this;
    }

    public function getHabilitado(): ?bool
    {
        return $this->habilitado;
    }

    public function setHabilitado(bool $habilitado): self
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    public function getEliminado(): ?bool
    {
        return $this->eliminado;
    }

    public function setEliminado(bool $eliminado): self
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * @return Collection|Cotizaciones[]
     */
    public function getCotizaciones(): Collection
    {
        return $this->cotizaciones;
    }

    public function addCotizacione(Cotizaciones $cotizacione): self
    {
        if (!$this->cotizaciones->contains($cotizacione)) {
            $this->cotizaciones[] = $cotizacione;
            $cotizacione->setIdCliente($this);
        }

        return $this;
    }

    public function removeCotizacione(Cotizaciones $cotizacione): self
    {
        if ($this->cotizaciones->contains($cotizacione)) {
            $this->cotizaciones->removeElement($cotizacione);
            // set the owning side to null (unless already changed)
            if ($cotizacione->getIdCliente() === $this) {
                $cotizacione->setIdCliente(null);
            }
        }

        return $this;
    }
    public function __toString() {
     return $this->getApellido() . ' ' . $this->getNombre();
    }

    /**
     * @return Collection|Ventas[]
     */
    public function getVentas(): Collection
    {
        return $this->ventas;
    }

    public function addVenta(Ventas $venta): self
    {
        if (!$this->ventas->contains($venta)) {
            $this->ventas[] = $venta;
            $venta->setCliente($this);
        }

        return $this;
    }

    public function removeVenta(Ventas $venta): self
    {
        if ($this->ventas->contains($venta)) {
            $this->ventas->removeElement($venta);
            // set the owning side to null (unless already changed)
            if ($venta->getCliente() === $this) {
                $venta->setCliente(null);
            }
        }

        return $this;
    }

    public function getCuit(): ?int
    {
        return $this->cuit;
    }

    public function setCuit(?int $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getRazonSocial(): ?string
    {
        return $this->razonSocial;
    }

    public function setRazonSocial(?string $razonSocial): self
    {
        $this->razonSocial = $razonSocial;

        return $this;
    }

    public function getCuentaCorriente(): ?bool
    {
        return $this->cuentaCorriente;
    }

    public function setCuentaCorriente(bool $cuentaCorriente): self
    {
        $this->cuentaCorriente = $cuentaCorriente;

        return $this;
    }

    /**
     * @return Collection|Reparaciones[]
     */
    public function getReparaciones(): Collection
    {
        return $this->reparaciones;
    }

    public function addReparacione(Reparaciones $reparacione): self
    {
        if (!$this->reparaciones->contains($reparacione)) {
            $this->reparaciones[] = $reparacione;
            $reparacione->setCliente($this);
        }

        return $this;
    }

    public function removeReparacione(Reparaciones $reparacione): self
    {
        if ($this->reparaciones->contains($reparacione)) {
            $this->reparaciones->removeElement($reparacione);
            // set the owning side to null (unless already changed)
            if ($reparacione->getCliente() === $this) {
                $reparacione->setCliente(null);
            }
        }

        return $this;
    }
}
