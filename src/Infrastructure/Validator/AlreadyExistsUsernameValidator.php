<?php

namespace App\Infrastructure\Validator;

use App\Domain\Security\Gateway\UserGateway;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AlreadyExistsUsernameValidator extends ConstraintValidator
{
    private UserGateway $userGateway;

    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($this->userGateway->usernameAlreadyExists($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
