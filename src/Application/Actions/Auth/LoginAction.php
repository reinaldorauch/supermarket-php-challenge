<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Auth\AuthAction;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Tuupola\Base62;

class LoginAction extends AuthAction
{
    public function action(): ResponseInterface
    {
        $data = $this->getFormData();
        $user = $this->userRepository->findByUsername($data['username']);
        $jwt = JWT::encode([
            'iat' => (new \DateTime())->getTimestamp(),
            'exp' => (new \DateTime('now +2 hours'))->getTimestamp(),
            'jti' => (new Base62)->encode(random_bytes(16)),
            "sub" => $user->getId(),
        ], $this->settings->get('secret'));
        return $this->respondWithData(['token' => $jwt]);
    }
}
