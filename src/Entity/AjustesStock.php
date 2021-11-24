<?php

namespace App\Entity;

use App\Repository\AjustesStockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AjustesStockRepository::class)
 */
class AjustesStock
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\ManyToOne(targetEntity=MotivosAjustesStock::class, inversedBy="ajustesStocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $motivo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="ajustesStocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock_anterior;

    /**
     * @ORM\ManyToOne(targetEntity=Productos::class, inversedBy="ajustesStocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="ajustesStocks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $almacen;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getMotivo(): ?MotivosAjustesStock
    {
        return $this->motivo;
    }

    public function setMotivo(?MotivosAjustesStock $motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getUsuario(): ?Usuarios
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuarios $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getStockAnterior(): ?int
    {
        return $this->stock_anterior;
    }

    public function setStockAnterior(int $stock_anterior): self
    {
        $this->stock_anterior = $stock_anterior;

        return $this;
    }

    public function getProducto(): ?Productos
    {
        return $this->producto;
    }

    public function setProducto(?Productos $producto): self
    {
        $this->producto = $producto;

        return $this;
    }

    public function getAlmacen(): ?Almacenes
    {
        return $this->almacen;
    }

    public function setAlmacen(?Almacenes $almacen): self
    {
        $this->almacen = $almacen;

        return $this;
    }
}
