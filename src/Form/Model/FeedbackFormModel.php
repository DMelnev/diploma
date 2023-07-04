<?php


namespace App\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

class FeedbackFormModel
{
    /**
     * @Assert\NotBlank(message="Введите Имя")
     */
    private string $name;

    /**
     * @Assert\NotBlank(message="Введите Email")
     * @Assert\Email(message="Email введен не корректно")
     */
    private string $email;

    /**
     * @Assert\NotBlank(message="Введите Текст")
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *     minMessage="Текст должен быть не менее 3 символов",
     *     maxMessage="Текст должен быть не более 255 символов"
     * )
     */
    private string $text;


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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }


}