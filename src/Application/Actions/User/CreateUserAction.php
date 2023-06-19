<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\DomainException\InvalidDataException;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\ValidationException;

class CreateUserAction extends UserAction
{
    public function action(): Response
    {
        $data = $this->getFormData();

        try {
            self::validator()->validate($data);
        } catch (ValidationException $e) {
            throw new InvalidDataException($e->getMessage());
        }

        $user = $this->userRepository->create($data);

        return $this->respondWithData($user);
    }

    public static function validator(): v
    {
        $stringValidator = v::alnum()->noWhitespace()->length(1, 255);
        return v::key('username', $stringValidator)
            ->key('firstName', $stringValidator)
            ->key('lastName', $stringValidator)
            ->key('password', $stringValidator)
            ->key('confirmPassword', v::keyValue('confirmPassword', 'equals', 'password'));
    }
}
