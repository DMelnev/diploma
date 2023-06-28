<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    private UserRepository $userRepository;
    private Security $security;

    /**
     * UniqueUserValidator constructor.
     */
    public function __construct(
        UserRepository $userRepository,
        Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var UniqueUser $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        /** @var User $user */
        $user = $this->security->getUser();

        if ($this->userRepository->findOneBy(['email' => $value]) && $user->getEmail() != $value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
