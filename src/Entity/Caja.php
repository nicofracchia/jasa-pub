<?php

namespace App\Entity;

use App\Repository\CajaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CajaRepository::class)
 */
class Caja
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $inicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cierre;

    /**
     * @ORM\Column(type="float")
     */
    private $saldoInicial;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $saldoEstimado;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $saldoFinal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="cajas")
     */
    private $usuarioApertura;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="cajasCierre")
     */
    private $usuarioCierre;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosCaja::class, mappedBy="caja")
     */
    private $movimientosCajas;

    public function __construct()
    {
        $this->movimientosCajas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInicio(): ?\DateTimeInterface
    {
        return $this->inicio;
    }

    public function setInicio(\DateTimeInterface $inicio): self
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getCierre(): ?\DateTimeInterface
    {
        return $this->cierre;
    }

    public function setCierre(?\DateTimeInterface $cierre): self
    {
        $this->cierre = $cierre;

        return $this;
    }

    public function getSaldoInicial(): ?float
    {
        return $this->saldoInicial;
    }

    public function setSaldoInicial(float $saldoInicial): self
    {
        $this->saldoInicial = $saldoInicial;

        return $this;
    }

    public function getSaldoEstimado(): ?float
    {
        return $this->saldoEstimado;
    }

    public function setSaldoEstimado(?float $saldoEstimado): self
    {
        $this->saldoEstimado = $saldoEstimado;

        return $this;
    }

    public function getSaldoFinal(): ?float
    {
        return $this->saldoFinal;
    }

    public function setSaldoFinal(?float $saldoFinal): self
    {
        $this->saldoFinal = $saldoFinal;

        return $this;
    }

    public function getEstado(): ?bool
    {
        return $this->estado;
    }

    public function setEstado(bool $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getUsuarioApertura(): ?Usuarios
    {
        return $this->usuarioApertura;
    }

    public function setUsuarioApertura(?Usuarios $usuarioApertura): self
    {
        $this->usuarioApertura = $usuarioApertura;

        return $this;
    }

    public function getUsuarioCierre(): ?Usuarios
    {
        return $this->usuarioCierre;
    }

    public function setUsuarioCierre(?Usuarios $usuarioCierre): self
    {
        $this->usuarioCierre = $usuarioCierre;

        return $this;
    }

    /**
     * @return Collection|MovimientosCaja[]
     */
    public function getMovimientosCajas(): Collection
    {
        return $this->movimientosCajas;
    }

    public function addMovimientosCaja(MovimientosCaja $movimientosCaja): self
    {
        if (!$this->movimientosCajas->contains($movimientosCaja)) {
            $this->movimientosCajas[] = $movimientosCaja;
            $movimientosCaja->setCaja($this);
        }

        return $this;
    }

    public function removeMovimientosCaja(MovimientosCaja $movimientosCaja): self
    {
        if ($this->movimientosCajas->contains($movimientosCaja)) {
            $this->movimientosCajas->removeElement($movimientosCaja);
            // set the owning side to null (unless already changed)
            if ($movimientosCaja->getCaja() === $this) {
                $movimientosCaja->setCaja(null);
            }
        }

        return $this;
    }
}
