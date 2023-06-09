<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\Mailer;
use App\Service\MainBaseService;
use App\Service\RolesConst;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController implements RolesConst
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, MainBaseService $service): Response
    {
        return $this->render('security/login.html.twig',
            $service->getAllBase([
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]));
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHash,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $em,
        Mailer $mailer,
        MainBaseService $service
    ): ?Response
    {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserRegistrationFormModel $userModel */
            $userModel = $form->getData();
            $user = new User;
            $user
                ->setName($userModel->getName())
                ->setEmail($userModel->getEmail())
                ->setPassword($passwordHash->hashPassword(
                    $user,
                    $userModel->getPlainPassword()
                ))
                ->setRoles(self::ROLE_USER)
                ->setActivationCode(substr(md5($user->getPassword() . rand(0, 999)), rand(0, 15), 16));

            if ($user->getEmail() && $mailer->sendWelcome($user)) {
                $em->persist($user);
                $em->flush();
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
//            } else {
////                $this->addFlash('flash_error', 'Почтовый сервер говорит, что такого E-mail не существует!');
            }
        }

        return $this->renderForm('security/sign_up.html.twig',
            $service->getAllBase([
                "registrationForm" => $form,
            ]));

    }
}
