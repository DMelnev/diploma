<?php


namespace App\Form\Model;

use App\Validator\MatchPhone;
use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserEditFormModel
{
    /**
     * @Assert\NotBlank(message="Enter your name!")
     * @Assert\Length(
     *     max="50",
     *     maxMessage="Name must be less than 50 symbols!"
     * )
     */
    private $name;

    /**
     * @Assert\Length(
     *     min="6",
     *     max="100",
     *     minMessage="Password must be more than 6 symbols!",
     *     maxMessage="Password must be less than 100 symbols!"
     * )
     */
    private ?string $plainPassword;

    /**
     * @MatchPhone()
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="Enter E-mail!")
     * @UniqueUser()
     */
    private $email;

    private $imageFilename;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword(): ?string
    {

        return $this->plainPassword ?? null;
    }


    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * @param mixed $imageFilename
     */
    public function setImageFilename($imageFilename): self
    {
        $this->imageFilename = $imageFilename;
        return $this;
    }
}