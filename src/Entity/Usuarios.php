<?php

namespace App\Entity;

use App\Repository\UsuariosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuariosRepository::class)
 */
class Usuarios implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity=Roles::class, inversedBy="usuarios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rol;

    /**
     * @ORM\ManyToOne(targetEntity=Almacenes::class, inversedBy="usuarios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $almacen;

    /**
     * @ORM\Column(type="boolean")
     */
    private $habilitado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminado = 0;

    /**
     * @ORM\OneToMany(targetEntity=AjustesStock::class, mappedBy="usuario")
     */
    private $ajustesStocks;

    /**
     * @ORM\OneToMany(targetEntity=Cotizaciones::class, mappedBy="creador")
     */
    private $cotizaciones;

    /**
     * @ORM\OneToMany(targetEntity=Ventas::class, mappedBy="creador")
     */
    private $ventas;

    /**
     * @ORM\OneToMany(targetEntity=Compras::class, mappedBy="creador")
     */
    private $compras;

    /**
     * @ORM\OneToMany(targetEntity=Caja::class, mappedBy="usuarioApertura")
     */
    private $cajas;

    /**
     * @ORM\OneToMany(targetEntity=Caja::class, mappedBy="usuarioCierre")
     */
    private $cajasCierre;

    /**
     * @ORM\OneToMany(targetEntity=MovimientosCaja::class, mappedBy="creador")
     */
    private $movimientosCajas;

    /**
     * @ORM\OneToMany(targetEntity=Reparaciones::class, mappedBy="receptor")
     */
    private $reparaciones;

    public function __construct()
    {
        $this->ajustesStocks = new ArrayCollection();
        $this->cotizaciones = new ArrayCollection();
        $this->ventas = new ArrayCollection();
        $this->compras = new ArrayCollection();
        $this->cajas = new ArrayCollection();
        $this->cajasCierre = new ArrayCollection();
        $this->movimientosCajas = new ArrayCollection();
        $this->reparaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
/*
        $roles[] = 'ROLE_ADMIN';
        $roles[] = 'ROLE_PRODUCTOS';
        $roles[] = 'ROLE_COMBOS';
        $roles[] = 'ROLE_REPARACIONES';
        $roles[] = 'ROLE_ALMACENES';
        $roles[] = 'ROLE_PROVEEDORES';
        $roles[] = 'ROLE_CLIENTES';
        $roles[] = 'ROLE_COTIZACIONES';
        $roles[] = 'ROLE_VENTAS';
        $roles[] = 'ROLE_COMPRAS';
        $roles[] = 'ROLE_GENERAL';
        */

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getRol(): ?Roles
    {
        return $this->rol;
    }

    public function setRol(Roles $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getAlmacen(): ?Almacenes
    {
        return $this->almacen;
    }

    public function setAlmacen(Almacenes $almacen): self
    {
        $this->almacen = $almacen;

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
     * @return Collection|AjustesStock[]
     */
    public function getAjustesStocks(): Collection
    {
        return $this->ajustesStocks;
    }

    public function addAjustesStock(AjustesStock $ajustesStock): self
    {
        if (!$this->ajustesStocks->contains($ajustesStock)) {
            $this->ajustesStocks[] = $ajustesStock;
            $ajustesStock->setUsuario($this);
        }

        return $this;
    }

    public function removeAjustesStock(AjustesStock $ajustesStock): self
    {
        if ($this->ajustesStocks->contains($ajustesStock)) {
            $this->ajustesStocks->removeElement($ajustesStock);
            // set the owning side to null (unless already changed)
            if ($ajustesStock->getUsuario() === $this) {
                $ajustesStock->setUsuario(null);
            }
        }

        return $this;
    }
    public function __toString() {
     return $this->getApellido() . ' ' . $this->getNombre();
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
            $cotizacione->setCreador($this);
        }

        return $this;
    }

    public function removeCotizacione(Cotizaciones $cotizacione): self
    {
        if ($this->cotizaciones->contains($cotizacione)) {
            $this->cotizaciones->removeElement($cotizacione);
            // set the owning side to null (unless already changed)
            if ($cotizacione->getCreador() === $this) {
                $cotizacione->setCreador(null);
            }
        }

        return $this;
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
            $venta->setCreador($this);
        }

        return $this;
    }

    public function removeVenta(Ventas $venta): self
    {
        if ($this->ventas->contains($venta)) {
            $this->ventas->removeElement($venta);
            // set the owning side to null (unless already changed)
            if ($venta->getCreador() === $this) {
                $venta->setCreador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compras[]
     */
    public function getCompras(): Collection
    {
        return $this->compras;
    }

    public function addCompra(Compras $compra): self
    {
        if (!$this->compras->contains($compra)) {
            $this->compras[] = $compra;
            $compra->setCreador($this);
        }

        return $this;
    }

    public function removeCompra(Compras $compra): self
    {
        if ($this->compras->contains($compra)) {
            $this->compras->removeElement($compra);
            // set the owning side to null (unless already changed)
            if ($compra->getCreador() === $this) {
                $compra->setCreador(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Caja[]
     */
    public function getCajas(): Collection
    {
        return $this->cajas;
    }

    public function addCaja(Caja $caja): self
    {
        if (!$this->cajas->contains($caja)) {
            $this->cajas[] = $caja;
            $caja->setUsuarioApertura($this);
        }

        return $this;
    }

    public function removeCaja(Caja $caja): self
    {
        if ($this->cajas->contains($caja)) {
            $this->cajas->removeElement($caja);
            // set the owning side to null (unless already changed)
            if ($caja->getUsuarioApertura() === $this) {
                $caja->setUsuarioApertura(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Caja[]
     */
    public function getCajasCierre(): Collection
    {
        return $this->cajasCierre;
    }

    public function addCajasCierre(Caja $cajasCierre): self
    {
        if (!$this->cajasCierre->contains($cajasCierre)) {
            $this->cajasCierre[] = $cajasCierre;
            $cajasCierre->setUsuarioCierre($this);
        }

        return $this;
    }

    public function removeCajasCierre(Caja $cajasCierre): self
    {
        if ($this->cajasCierre->contains($cajasCierre)) {
            $this->cajasCierre->removeElement($cajasCierre);
            // set the owning side to null (unless already changed)
            if ($cajasCierre->getUsuarioCierre() === $this) {
                $cajasCierre->setUsuarioCierre(null);
            }
        }

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
            $movimientosCaja->setCreador($this);
        }

        return $this;
    }

    public function removeMovimientosCaja(MovimientosCaja $movimientosCaja): self
    {
        if ($this->movimientosCajas->contains($movimientosCaja)) {
            $this->movimientosCajas->removeElement($movimientosCaja);
            // set the owning side to null (unless already changed)
            if ($movimientosCaja->getCreador() === $this) {
                $movimientosCaja->setCreador(null);
            }
        }

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
            $reparacione->setReceptor($this);
        }

        return $this;
    }

    public function removeReparacione(Reparaciones $reparacione): self
    {
        if ($this->reparaciones->contains($reparacione)) {
            $this->reparaciones->removeElement($reparacione);
            // set the owning side to null (unless already changed)
            if ($reparacione->getReceptor() === $this) {
                $reparacione->setReceptor(null);
            }
        }

        return $this;
    }
}
