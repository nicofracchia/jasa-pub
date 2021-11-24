<?php

namespace App\Entity;

use App\Repository\MovimientosAlmacenesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovimientosAlmacenesRepository::class)
 */
class MovimientosAlmacenes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="movimientosAlmacenesDesde")
     */
    private $desde;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="movimientosAlmacenesHacia")
     */
    private $hacia;

    /**
     * @ORM\Column(type="smallint")
     */
    private $estado;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pedido;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $envio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $recepcion;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenesPedidos::class, mappedBy="movimiento")
     */
    private $movimientosAlmacenesPedidos;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenesEnvios::class, mappedBy="movimiento")
     */
    private $movimientosAlmacenesEnvios;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenesRecepciones::class, mappedBy="movimiento")
     */
    private $movimientosAlmacenesRecepciones;

    public function __construct()
    {
        $this->movimientosAlmacenesPedidos = new ArrayCollection();
        $this->movimientosAlmacenesEnvios = new ArrayCollection();
        $this->movimientosAlmacenesRecepciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesde(): ?Almacenes
    {
        return $this->desde;
    }

    public function setDesde(?Almacenes $desde): self
    {
        $this->desde = $desde;

        return $this;
    }

    public function getHacia(): ?Almacenes
    {
        return $this->hacia;
    }

    public function setHacia(?Almacenes $hacia): self
    {
        $this->hacia = $hacia;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->estado;
    }

    public function setEstado(int $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getPedido(): ?\DateTimeInterface
    {
        return $this->pedido;
    }

    public function setPedido(\DateTimeInterface $pedido): self
    {
        $this->pedido = $pedido;

        return $this;
    }

    public function getEnvio(): ?\DateTimeInterface
    {
        return $this->envio;
    }

    public function setEnvio(?\DateTimeInterface $envio): self
    {
        $this->envio = $envio;

        return $this;
    }

    public function getRecepcion(): ?\DateTimeInterface
    {
        return $this->recepcion;
    }

    public function setRecepcion(?\DateTimeInterface $recepcion): self
    {
        $this->recepcion = $recepcion;

        return $this;
    }

    /**
     * @return Collection|MovimientosAlmacenesPedidos[]
     */
    public function getMovimientosAlmacenesPedidos(): Collection
    {
        return $this->movimientosAlmacenesPedidos;
    }

    public function addMovimientosAlmacenesPedido(MovimientosAlmacenesPedidos $movimientosAlmacenesPedido): self
    {
        if (!$this->movimientosAlmacenesPedidos->contains($movimientosAlmacenesPedido)) {
            $this->movimientosAlmacenesPedidos[] = $movimientosAlmacenesPedido;
            $movimientosAlmacenesPedido->setPedido($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesPedido(MovimientosAlmacenesPedidos $movimientosAlmacenesPedido): self
    {
        if ($this->movimientosAlmacenesPedidos->contains($movimientosAlmacenesPedido)) {
            $this->movimientosAlmacenesPedidos->removeElement($movimientosAlmacenesPedido);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesPedido->getPedido() === $this) {
                $movimientosAlmacenesPedido->setPedido(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovimientosAlmacenesEnvios[]
     */
    public function getMovimientosAlmacenesEnvios(): Collection
    {
        return $this->movimientosAlmacenesEnvios;
    }

    public function addMovimientosAlmacenesEnvio(MovimientosAlmacenesEnvios $movimientosAlmacenesEnvio): self
    {
        if (!$this->movimientosAlmacenesEnvios->contains($movimientosAlmacenesEnvio)) {
            $this->movimientosAlmacenesEnvios[] = $movimientosAlmacenesEnvio;
            $movimientosAlmacenesEnvio->setMovimiento($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesEnvio(MovimientosAlmacenesEnvios $movimientosAlmacenesEnvio): self
    {
        if ($this->movimientosAlmacenesEnvios->contains($movimientosAlmacenesEnvio)) {
            $this->movimientosAlmacenesEnvios->removeElement($movimientosAlmacenesEnvio);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesEnvio->getMovimiento() === $this) {
                $movimientosAlmacenesEnvio->setMovimiento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovimientosAlmacenesRecepciones[]
     */
    public function getMovimientosAlmacenesRecepciones(): Collection
    {
        return $this->movimientosAlmacenesRecepciones;
    }

    public function addMovimientosAlmacenesRecepcione(MovimientosAlmacenesRecepciones $movimientosAlmacenesRecepcione): self
    {
        if (!$this->movimientosAlmacenesRecepciones->contains($movimientosAlmacenesRecepcione)) {
            $this->movimientosAlmacenesRecepciones[] = $movimientosAlmacenesRecepcione;
            $movimientosAlmacenesRecepcione->setMovimiento($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesRecepcione(MovimientosAlmacenesRecepciones $movimientosAlmacenesRecepcione): self
    {
        if ($this->movimientosAlmacenesRecepciones->contains($movimientosAlmacenesRecepcione)) {
            $this->movimientosAlmacenesRecepciones->removeElement($movimientosAlmacenesRecepcione);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesRecepcione->getMovimiento() === $this) {
                $movimientosAlmacenesRecepcione->setMovimiento(null);
            }
        }

        return $this;
    }
}
