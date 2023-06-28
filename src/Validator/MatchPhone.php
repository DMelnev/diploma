<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class MatchPhone extends Constraint
{
    public $message = 'Указанного номера не существует.';
}
