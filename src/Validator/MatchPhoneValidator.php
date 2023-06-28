<?php

namespace App\Validator;

use App\Service\PhoneNumberFilter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MatchPhoneValidator extends ConstraintValidator
{
    private PhoneNumberFilter $filter;

    /**
     * MatchPhoneValidator constructor.
     */
    public function __construct(PhoneNumberFilter $filter)
    {
        $this->filter = $filter;
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var MatchPhone $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        if (!$this->filter->filter($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
