<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Auth;

use App\Infrastructure\Persistence\User\DatabaseUserRepository;
use Respect\Validation\Validator as v;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    public function testLoginAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $userRepo = $container->get(DatabaseUserRepository::class);

        $creds = ['username' => 'jagunco', 'password' => '12345'];
        $userRepo->create([
            ...$creds,
            'firstName' => 'Jagunço',
            'lastName' => 'Lala'
        ]);

        $body = json_encode($creds);
        $req = $this->createRequest(
            'POST',
            '/auth/login',
            ['HTTP_CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
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
    }
}
