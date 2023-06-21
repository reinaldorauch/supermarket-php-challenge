<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Infrastructure\Persistence\User\DatabaseUserRepository;
use Respect\Validation\Validator as v;
use Slim\App;
use DI\Container;
use Slim\Exception\HttpUnauthorizedException;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private App $app;
    private Container $container;

    public function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->container = $this->app->getContainer();
    }

    public function testLoginAction()
    {
        $userRepo = $this->container->get(DatabaseUserRepository::class);

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
        $res = $this->app->handle($req);

        $data = (string) $res->getBody();
        $payload = json_decode($data, true);

        $validator = v::key('data', v::key('token', v::stringType()))
            ->key('statusCode', v::equals(200));

        $this->assertNull($validator->assert($payload));
    }

    public function testShouldReturnUnauthorizedWhenUserNotFound()
    {
        $this->expectException(HttpUnauthorizedException::class);

        $body = json_encode(['username' => 'jagunco', 'password' => '12345']);
        $req = $this->createRequest(
            'POST',
            '/auth/login',
            ['HTTP_CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            [],
            [],
            $body
        );
        $res = $this->app->handle($req);

        $data = (string) $res->getBody();
        $payload = json_decode($data, true);

        $validator = v::key('statusCode', v::equals(401));

        $this->assertNull($validator->assert($payload));
    }
}
