<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Auth;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Firebase\JWT\JWT;
use Respect\Validation\Validator as v;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    public function testLoginAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $creds = ['username' => 'jagunco', 'password' => '12345'];

        $user = User::from(
            69,
            $creds['username'],
            'Jagunço',
            'Lala'
        );
        $user->setPassword($creds['password']);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findByUsername($creds['username'])
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $body = json_encode($creds);
        $req = $this->createRequest(
            'POST',
            '/auth/login',
            [
                'HTTP_CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            [],
            [],
            $body
        );
        $res = $app->handle($req);

        $data = (string) $res->getBody();
        $payload = json_decode($data, true);

        $validator = v::key('data', v::key('token', v::stringType()))
            ->key('statusCode', v::equals(200));

        $this->assertNull($validator->assert($payload));
        $this->assertEquals(
            $user->getId(),
            JWT::decode(
                $payload['data']['token'],
                $_ENV['SECRET'],
                ['HS256']
            )->sub
        );
    }
}
