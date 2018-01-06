<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class dni extends Constraint
{
    public $message = 'El DNI introducido no es válido.';
}