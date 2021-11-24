<?php

namespace App\Entity;

use App\Repository\ReparacionesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReparacionesRepository::class)
 */
class Reparaciones
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
    private $recepcion;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="reparaciones")
     */
    private $almacen;

    /**
     * @ORM\ManyToOne(targetEntity=Clientes::class, inversedBy="reparaciones")
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="reparaciones")
     */
    private $receptor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $articulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marca;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modelo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serial;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tarea;

    /**
     * @ORM\Column(type="text")
     */
    private $reporte;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tintaC;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tintaM;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tintaY;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tintaCl;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tintaMl;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tintaBk;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estado;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $diagnostico;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $presupuestoInicial;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $presupuestoFinal;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\OneToMany(targetEntity=ReparacionesPagos::class, mappedBy="reparacion")
     */
    private $reparacionesPagos;

    /**
     * @ORM\OneToMany(targetEntity=ReparacionesEstados::class, mappedBy="reparacion")
     */
    private $reparacionesEstados;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sena;

    /**
     * @ORM\OneToMany(targetEntity=ReparacionesProductos::class, mappedBy="reparacion")
     */
    private $reparacionesProductos;

    public function __construct()
    {
        $this->reparacionesPagos = new ArrayCollection();
        $this->reparacionesEstados = new ArrayCollection();
        $this->reparacionesProductos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecepcion(): ?\DateTimeInterface
    {
        return $this->recepcion;
    }

    public function setRecepcion(\DateTimeInterface $recepcion): self
    {
        $this->recepcion = $recepcion;

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

    public function getCliente(): ?Clientes
    {
        return $this->cliente;
    }

    public function setCliente(?Clientes $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getReceptor(): ?Usuarios
    {
        return $this->receptor;
    }

    public function setReceptor(?Usuarios $receptor): self
    {
        $this->receptor = $receptor;

        return $this;
    }

    public function getArticulo(): ?string
    {
        return $this->articulo;
    }

    public function setArticulo(string $articulo): self
    {
        $this->articulo = $articulo;

        return $this;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): self
    {
        $this->marca = $marca;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(string $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(string $serial): self
    {
        $this->serial = $serial;

        return $this;
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

    public function getReporte(): ?string
    {
        return $this->reporte;
    }

    public function setReporte(string $reporte): self
    {
        $this->reporte = $reporte;

        return $this;
    }

    public function getTintaC(): ?float
    {
        return $this->tintaC;
    }

    public function setTintaC(?float $tintaC): self
    {
        $this->tintaC = $tintaC;

        return $this;
    }

    public function getTintaM(): ?float
    {
        return $this->tintaM;
    }

    public function setTintaM(?float $tintaM): self
    {
        $this->tintaM = $tintaM;

        return $this;
    }

    public function getTintaY(): ?float
    {
        return $this->tintaY;
    }

    public function setTintaY(?float $tintaY): self
    {
        $this->tintaY = $tintaY;

        return $this;
    }

    public function getTintaCl(): ?float
    {
        return $this->tintaCl;
    }

    public function setTintaCl(?float $tintaCl): self
    {
        $this->tintaCl = $tintaCl;

        return $this;
    }

    public function getTintaMl(): ?float
    {
        return $this->tintaMl;
    }

    public function setTintaMl(?float $tintaMl): self
    {
        $this->tintaMl = $tintaMl;

        return $this;
    }

    public function getTintaBk(): ?float
    {
        return $this->tintaBk;
    }

    public function setTintaBk(?float $tintaBk): self
    {
        $this->tintaBk = $tintaBk;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getDiagnostico(): ?string
    {
        return $this->diagnostico;
    }

    public function setDiagnostico(?string $diagnostico): self
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    public function getPresupuestoInicial(): ?float
    {
        return $this->presupuestoInicial;
    }

    public function setPresupuestoInicial(?float $presupuestoInicial): self
    {
        $this->presupuestoInicial = $presupuestoInicial;

        return $this;
    }

    public function getPresupuestoFinal(): ?float
    {
        return $this->presupuestoFinal;
    }

    public function setPresupuestoFinal(?float $presupuestoFinal): self
    {
        $this->presupuestoFinal = $presupuestoFinal;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * @return Collection|ReparacionesPagos[]
     */
    public function getReparacionesPagos(): Collection
    {
        return $this->reparacionesPagos;
    }

    public function addReparacionesPago(ReparacionesPagos $reparacionesPago): self
    {
        if (!$this->reparacionesPagos->contains($reparacionesPago)) {
            $this->reparacionesPagos[] = $reparacionesPago;
            $reparacionesPago->setReparacion($this);
        }

        return $this;
    }

    public function removeReparacionesPago(ReparacionesPagos $reparacionesPago): self
    {
        if ($this->reparacionesPagos->contains($reparacionesPago)) {
            $this->reparacionesPagos->removeElement($reparacionesPago);
            // set the owning side to null (unless already changed)
            if ($reparacionesPago->getReparacion() === $this) {
                $reparacionesPago->setReparacion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReparacionesEstados[]
     */
    public function getReparacionesEstados(): Collection
    {
        return $this->reparacionesEstados;
    }

    public function addReparacionesEstado(ReparacionesEstados $reparacionesEstado): self
    {
        if (!$this->reparacionesEstados->contains($reparacionesEstado)) {
            $this->reparacionesEstados[] = $reparacionesEstado;
            $reparacionesEstado->setReparacion($this);
        }

        return $this;
    }

    public function removeReparacionesEstado(ReparacionesEstados $reparacionesEstado): self
    {
        if ($this->reparacionesEstados->contains($reparacionesEstado)) {
            $this->reparacionesEstados->removeElement($reparacionesEstado);
            // set the owning side to null (unless already changed)
            if ($reparacionesEstado->getReparacion() === $this) {
                $reparacionesEstado->setReparacion(null);
            }
        }

        return $this;
    }

    public function getSena(): ?float
    {
        return $this->sena;
    }

    public function setSena(?float $sena): self
    {
        $this->sena = $sena;

        return $this;
    }

    /**
     * @return Collection|ReparacionesProductos[]
     */
    public function getReparacionesProductos(): Collection
    {
        return $this->reparacionesProductos;
    }

    public function addReparacionesProducto(ReparacionesProductos $reparacionesProducto): self
    {
        if (!$this->reparacionesProductos->contains($reparacionesProducto)) {
            $this->reparacionesProductos[] = $reparacionesProducto;
            $reparacionesProducto->setReparacion($this);
        }

        return $this;
    }

    public function removeReparacionesProducto(ReparacionesProductos $reparacionesProducto): self
    {
        if ($this->reparacionesProductos->contains($reparacionesProducto)) {
            $this->reparacionesProductos->removeElement($reparacionesProducto);
            // set the owning side to null (unless already changed)
            if ($reparacionesProducto->getReparacion() === $this) {
                $reparacionesProducto->setReparacion(null);
            }
        }

        return $this;
    }
}
