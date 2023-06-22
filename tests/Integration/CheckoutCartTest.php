<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Domain\CheckoutCart\CheckoutCartRepository;
use DI\Container;
use Fig\Http\Message\StatusCodeInterface;
use Prophecy\Argument;
use Respect\Validation\Validator as v;
use Tests\TestCase;

class CheckoutCartTest extends TestCase
{
    public function testCreateCheckoutCartShouldFailIfNoToken()
    {
        $app = $this->getAppInstance();
        /** @var Container */
        $container = $app->getContainer();

        $repoProphecy = $this->prophesize(CheckoutCartRepository::class);
        $repoProphecy->create(Argument::type('int'))->shouldNotBeCalled();

        $container->set(CheckoutCartRepository::class, $repoProphecy->reveal());

        $request = $this->createRequest('POST', '/checkout-cart');
        $response = $app->handle($request);
        $this->assertEquals(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testShouldCreateCheckoutCartWithAuthorizedUser()
    {
        $app = $this->getAppInstance();

        [, $token] = $this->logInWithUser($app);

        $request = $this->createRequest(
            'POST',
            '/checkout-cart',
            ['HTTP_AUTHORIZATION' => "Bearer {$token}"]
        );

        $response  = $app->handle($request);
        $data = json_decode((string) $response->getBody(), true);

        $result = v::key('statusCode', v::equals(201))
            ->key(
                'data',
                v::key('id', v::equals(1))
                    ->key('items', v::equals([]))
                    ->key('total', v::equals(0))
                    ->key('totalTax', v::equals(0))
            )
            ->assert($data);
        $this->assertNull($result);
    }
}
