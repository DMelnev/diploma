<?php


namespace App\Form\Model;


use App\Entity\User;

class FilterFormModel
{
private string $price;
private string $title;
private User $seller;
private bool $brokenScreen;
private string $memoryValue;


    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return User
     */
    public function getSeller(): User
    {
        return $this->seller;
    }


    public function setSeller(User $seller): self
    {
        $this->seller = $seller;
        return $this;
    }


    public function isBrokenScreen(): bool
    {
        return $this->brokenScreen;
    }

    /**
     * @param bool $brokenScreen
     */
    public function setBrokenScreen(bool $brokenScreen): self
    {
        $this->brokenScreen = $brokenScreen;
        return $this;
    }

    /**
     * @return string
     */
    public function getMemoryValue(): string
    {
        return $this->memoryValue;
    }

    /**
     * @param string $memoryValue
     */
    public function setMemoryValue(string $memoryValue): self
    {
        $this->memoryValue = $memoryValue;
        return $this;
    }

}