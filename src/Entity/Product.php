<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Product
{
    use SoftDeleteableEntity;

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
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $short_description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Unit::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\Column(type="integer")
     */
    private $dislikes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $limited;

    /**
     * @ORM\ManyToOne(targetEntity=Action::class, inversedBy="product")
     */
    private $action;

    /**
     * @ORM\OneToMany(targetEntity=DayOffer::class, mappedBy="product")
     */
    private $dayOffers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=ProductProperty::class, mappedBy="product")
     */
    private $productProperties;

    /**
     * @ORM\OneToMany(targetEntity=Feedback::class, mappedBy="product")
     */
    private $feedback;

    /**
     * @ORM\OneToMany(targetEntity=ShowHistory::class, mappedBy="product")
     */
    private $showHistories;

    /**
     * @ORM\OneToMany(targetEntity=Price::class, mappedBy="product")
     */
    private $prices;

    public function __construct()
    {
        $this->dayOffers = new ArrayCollection();
        $this->productPictures = new ArrayCollection();
        $this->productProperties = new ArrayCollection();
        $this->feedback = new ArrayCollection();
        $this->showHistories = new ArrayCollection();
        $this->prices = new ArrayCollection();
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

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): ?int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    public function isLimited(): ?bool
    {
        return $this->limited;
    }

    public function setLimited(?bool $limited): self
    {
        $this->limited = $limited;

        return $this;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return Collection<int, DayOffer>
     */
    public function getDayOffers(): Collection
    {
        return $this->dayOffers;
    }

    public function addDayOffer(DayOffer $dayOffer): self
    {
        if (!$this->dayOffers->contains($dayOffer)) {
            $this->dayOffers[] = $dayOffer;
            $dayOffer->setProduct($this);
        }

        return $this;
    }

    public function removeDayOffer(DayOffer $dayOffer): self
    {
        if ($this->dayOffers->removeElement($dayOffer)) {
            // set the owning side to null (unless already changed)
            if ($dayOffer->getProduct() === $this) {
                $dayOffer->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductPicture>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
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
            $productProperty->setProduct($this);
        }

        return $this;
    }

    public function removeProductProperty(ProductProperty $productProperty): self
    {
        if ($this->productProperties->removeElement($productProperty)) {
            // set the owning side to null (unless already changed)
            if ($productProperty->getProduct() === $this) {
                $productProperty->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback[] = $feedback;
            $feedback->setProduct($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getProduct() === $this) {
                $feedback->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShowHistory>
     */
    public function getShowHistories(): Collection
    {
        return $this->showHistories;
    }

    public function addShowHistory(ShowHistory $showHistory): self
    {
        if (!$this->showHistories->contains($showHistory)) {
            $this->showHistories[] = $showHistory;
            $showHistory->setProduct($this);
        }

        return $this;
    }

    public function removeShowHistory(ShowHistory $showHistory): self
    {
        if ($this->showHistories->removeElement($showHistory)) {
            // set the owning side to null (unless already changed)
            if ($showHistory->getProduct() === $this) {
                $showHistory->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getProduct() === $this) {
                $price->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture): self
    {
        $this->picture = $picture;
        return $this;
    }
}
