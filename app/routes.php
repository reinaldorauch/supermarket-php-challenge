<?php

declare(strict_types=1);

use App\Application\Actions\Auth\LoginAction;
use App\Application\Actions\CheckoutCart\{AddProductCheckoutCartAction, CreateCheckoutCartAction, FinalizeCheckoutCartAction, ViewCheckoutCartAction, ViewCurrentCheckoutCartAction};
use App\Application\Actions\Product\{CreateProductAction, ListProductsAction, ViewProductAction};
use App\Application\Actions\ProductType\{CreateProductTypeAction, ListProductTypesAction};
use App\Application\Actions\User\{CreateUserAction, ListUsersAction, ViewUserAction};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response->withHeader('Access-Control-Allow-Origin', '*');
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/auth', function (Group $group) {
        $group->post('/login', LoginAction::class);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->post('', CreateUserAction::class);
    });

    $app->group('/product', function (Group $group) {
        $group->get('', ListProductsAction::class);
        $group->post('', CreateProductAction::class);
        $group->get('/{id}', ViewProductAction::class);
    });

    $app->group('/product-type', function (Group $group) {
        $group->get('', ListProductTypesAction::class);
        $group->post('', CreateProductTypeAction::class);
    });

    $app->group('/checkout-cart', function (Group $group) {
        $group->post('', CreateCheckoutCartAction::class);
        $group->get('/current', ViewCurrentCheckoutCartAction::class);
        $group->get('/{id}', ViewCheckoutCartAction::class);
        $group->post('/{id}/add', AddProductCheckoutCartAction::class);
        $group->post('/{id}/finalize', FinalizeCheckoutCartAction::class);
    });
};
