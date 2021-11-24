<?php

namespace App\Entity;

use App\Repository\MotivosAjustesStockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MotivosAjustesStockRepository::class)
 */
class MotivosAjustesStock
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
     * @ORM\OneToMany(targetEntity=AjustesStock::class, mappedBy="motivo")
     */
    private $ajustesStocks;

    /**
     * @ORM\Column(type="smallint")
     */
    private $tipo;

    public function __construct()
    {
        $this->ajustesStocks = new ArrayCollection();
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
            $ajustesStock->setMotivo($this);
        }

        return $this;
    }

    public function removeAjustesStock(AjustesStock $ajustesStock): self
    {
        if ($this->ajustesStocks->contains($ajustesStock)) {
            $this->ajustesStocks->removeElement($ajustesStock);
            // set the owning side to null (unless already changed)
            if ($ajustesStock->getMotivo() === $this) {
                $ajustesStock->setMotivo(null);
            }
        }

        return $this;
    }
    public function __toString() {
     return $this->getNombre();
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
