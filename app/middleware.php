<?php

declare(strict_types=1);

use App\Application\Middleware\JsonBodyParserMiddleware;
use App\Application\Middleware\SessionMiddleware;
use App\Application\Settings\SettingsInterface;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(JsonBodyParserMiddleware::class);

    $settings = $app->getContainer()->get(SettingsInterface::class);
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        'secret' => $settings->get('secret'),
        'ignore' => ['/auth/login', '/test-action-response-code'],
        'attribute' => 'tokenPayload',
        'secure' => false, // Using this bacause we use a proxy in prod
    ]));
};