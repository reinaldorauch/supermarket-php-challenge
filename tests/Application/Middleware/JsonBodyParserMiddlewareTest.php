<?php

declare(strict_types=1);

namespace Tests\Application\Middleware;

use App\Application\Middleware\JsonBodyParserMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tests\TestCase;

class JsonBodyParserMiddlewareTest extends TestCase
{
    public function testShouldCallRequestGetBody()
    {
        $requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $requestProphecy
            ->getBody()
            ->shouldBeCalledOnce();
        $requestProphecy->getHeaderLine('Content-Type')
            ->willReturn('application/json')
            ->shouldBeCalledOnce();

        $response = $this->prophesize(ResponseInterface::class);

        $handlerProphecy = $this->prophesize(RequestHandlerInterface::class);
        $reqInstance = $requestProphecy->reveal();
        $handlerProphecy->handle($reqInstance)
            ->willReturn($response->reveal())
            ->shouldBeCalledOnce();

        $middleware = new JsonBodyParserMiddleware();
        $middleware->process($reqInstance, $handlerProphecy->reveal());
    }
}
