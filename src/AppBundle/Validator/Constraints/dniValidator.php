<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class dniValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $letra = substr($value, -1);
        $numeros = substr($value, 0, -1);
        if (!(substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1) == $letra && strlen($letra) == 1 && strlen($numeros) == 8)) {
            $this->context->addViolation($constraint->message);
        }
    }
}