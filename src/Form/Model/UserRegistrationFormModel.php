<?php


namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Введите Имя")
     */
    private string $name;

    /**
     * @Assert\NotBlank(message="Введите Email")
     * @Assert\Email(message="Email введен не корректно")
     * @UniqueUser()
     */
    private string $email;

    /**
     * @Assert\NotBlank(message="Введите Пароль")
     * @Assert\Length(
     *     min="6",
     *     max="100",
     *     minMessage="Пароль должен быть не менее 6 символов",
     *     maxMessage="Пароль должен быть не более 100 символов"
     * )
     */
    private string $plainPassword;

    /**
     * @Assert\IsTrue(message="Вы не можете зарегистрироваться, если не согласны с условиями")
     */
    private string $agreeTerms;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getAgreeTerms(): string
    {
        return $this->agreeTerms;
    }

    /**
     * @param string $agreeTerms
     */
    public function setAgreeTerms(string $agreeTerms): void
    {
        $this->agreeTerms = $agreeTerms;
    }


}