<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 */
class Property
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Unit::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity=PropertyGroup::class, inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pGroup;

    /**
     * @ORM\OneToMany(targetEntity=ProductProperty::class, mappedBy="property")
     */
    private $productProperties;

    public function __construct()
    {
        $this->productProperties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getPGroup(): ?PropertyGroup
    {
        return $this->pGroup;
    }

    public function setPGroup(?PropertyGroup $pGroup): self
    {
        $this->pGroup = $pGroup;

        return $this;
    }

    /**
     * @return Collection<int, ProductProperty>
     */
    public function getProductProperties(): Collection
    {
        return $this->productProperties;
    }

    public function addProductProperty(ProductProperty $productProperty): self
    {
        if (!$this->productProperties->contains($productProperty)) {
            $this->productProperties[] = $productProperty;
            $productProperty->setProperty($this);
        }

        return $this;
    }

    public function removeProductProperty(ProductProperty $productProperty): self
    {
        if ($this->productProperties->removeElement($productProperty)) {
            // set the owning side to null (unless already changed)
            if ($productProperty->getProperty() === $this) {
                $productProperty->setProperty(null);
            }
        }

        return $this;
    }
}
