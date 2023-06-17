<?php


namespace App\Service;

use App\Entity\User;
use Closure;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    private MailerInterface $mailer;
    private string $systemEmail;
    private string $emailName;

    public function __construct(MailerInterface $mailer, $systemEmail, $emailName)
    {
        $this->mailer = $mailer;
        $this->systemEmail = $systemEmail;
        $this->emailName = $emailName;
    }

    /**
     * @param User|string $user
     * @param string $subject
     * @param string $text
     * @param string $htmlTemplate
     * @param Closure|null $callback
     * @throws TransportExceptionInterface
     */
    private function send($user, string $subject, string $text, string $htmlTemplate, \Closure $callback = null): bool
    {
        if (is_string($user)) {
            $email = $user;
            $name = $user;
        } else {
            $email = $user->getEmail();
            $name = $user->getName();
        }
        $email = (new TemplatedEmail())
            ->from(new Address($this->systemEmail, $this->emailName))
            ->to(new Address($email, $name))
            ->subject($subject)
            ->text($text)
            ->htmlTemplate($htmlTemplate)
        ;
        if ($callback) {
            $callback($email);
        }
        try {
            $this->mailer->send($email);
            return true;
        } catch (TransportExceptionInterface $exception) {
            return false;
        }


    }

    /**
     * @param User $user
     * @return bool
     * @throws TransportExceptionInterface
     */
    public function sendWelcome(User $user): bool
    {
        return $this->send(
            $user,
            'Добро пожаловать на сайт',
            'Ваш почтовый клиент не поддерживает В личном кабинете нажмите кнопку  и введите этот код: ' . $user->getActivationCode(),
            'mailer/welcome.html.twig',
            function (TemplatedEmail $email) use ($user) {
                $email
                    ->context([
                        'user' => $user,
                    ]);
            }
        );


    }

}