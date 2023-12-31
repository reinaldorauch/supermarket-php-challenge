<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'docker' => (bool) ($_ENV['docker'] ?? false),
                'displayErrorDetails' => true,
                // Should be set to false in production
                'logError' => false,
                'logErrorDetails' => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'databaseUrl' => ($_ENV['DATABASE_URL'] ?? 'pgsql:host=localhost;dbname=softech-php-challenge;user=postgres;password=amigen'),
                'secret' => ($_ENV['SECRET'] ?? 'acompleterandomsecret'),
                'production' => (bool) ($_ENV['production'] ?? false)
            ]);
        }
    ]);
};