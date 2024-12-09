<?php

namespace App\Infrastructure\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class OpenIdServerException extends AuthenticationException
{
}
