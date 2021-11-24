<?php

namespace App\Entity;

use App\Repository\ProductosAlmacenesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductosAlmacenesRepository::class)
 */
class ProductosAlmacenes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="productosAlmacenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_producto;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="productosAlmacenes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_almacen;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenesPedidos::class, mappedBy="producto")
     */
    private $movimientosAlmacenesPedidos;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenesEnvios::class, mappedBy="producto")
     */
    private $movimientosAlmacenesEnvios;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosAlmacenesRecepciones::class, mappedBy="producto")
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

    public function getIdProducto(): ?Productos
    {
        return $this->id_producto;
    }

    public function setIdProducto(?Productos $id_producto): self
    {
        $this->id_producto = $id_producto;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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
            $movimientosAlmacenesPedido->setProducto($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesPedido(MovimientosAlmacenesPedidos $movimientosAlmacenesPedido): self
    {
        if ($this->movimientosAlmacenesPedidos->contains($movimientosAlmacenesPedido)) {
            $this->movimientosAlmacenesPedidos->removeElement($movimientosAlmacenesPedido);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesPedido->getProducto() === $this) {
                $movimientosAlmacenesPedido->setProducto(null);
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
            $movimientosAlmacenesEnvio->setProducto($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesEnvio(MovimientosAlmacenesEnvios $movimientosAlmacenesEnvio): self
    {
        if ($this->movimientosAlmacenesEnvios->contains($movimientosAlmacenesEnvio)) {
            $this->movimientosAlmacenesEnvios->removeElement($movimientosAlmacenesEnvio);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesEnvio->getProducto() === $this) {
                $movimientosAlmacenesEnvio->setProducto(null);
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
            $movimientosAlmacenesRecepcione->setProducto($this);
        }

        return $this;
    }

    public function removeMovimientosAlmacenesRecepcione(MovimientosAlmacenesRecepciones $movimientosAlmacenesRecepcione): self
    {
        if ($this->movimientosAlmacenesRecepciones->contains($movimientosAlmacenesRecepcione)) {
            $this->movimientosAlmacenesRecepciones->removeElement($movimientosAlmacenesRecepcione);
            // set the owning side to null (unless already changed)
            if ($movimientosAlmacenesRecepcione->getProducto() === $this) {
                $movimientosAlmacenesRecepcione->setProducto(null);
            }
        }

        return $this;
    }
}
