<?php

declare(strict_types=1);

namespace Tests;

use App\Application\Settings\SettingsInterface;
use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

class TestCase extends PHPUnit_TestCase
{
    use ProphecyTrait;

    /**
     * @return App
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        // Container intentionally not compiled for tests.

        // Set up settings
        $settings = require __DIR__ . '/../app/settings.php';
        $settings($containerBuilder);

        // Initializing empty database
        // We need to do this before intialization of the main db connection
        // to not get blocked when running the tests
        $this->resetDatabase();

        // Set up dependencies
        $dependencies = require __DIR__ . '/../app/dependencies.php';
        $dependencies($containerBuilder);

        // Set up repositories
        $repositories = require __DIR__ . '/../app/repositories.php';
        $repositories($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Register middleware
        $middleware = require __DIR__ . '/../app/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../app/routes.php';
        $routes($app);

        return $app;
    }

    protected function resetDatabase()
    {
        print_r($_ENV);
        $dsn = $_ENV['DATABASE_URL'];
        if (!is_string($dsn)) throw new \Exception('Invalid config type');

        $db = preg_replace('/^.+dbname=([^;]+);.+$/', '\1', $dsn);
        $origConnString = str_replace(';', ' ', preg_replace('/^pgsql:(.+)$/', '\1', $dsn));
        $connString = preg_replace('/dbname=[^\s]+/', '', $origConnString);

        // To be able to drop a database we need to connect without specifying it
        $conn = \pg_connect($connString) or throw new \Exception('Could not connect to postgres');
        $db = \pg_escape_identifier($conn, $db);

        \pg_query($conn, "DROP DATABASE IF EXISTS $db");
        \pg_query($conn, "CREATE DATABASE $db");

        \pg_close($conn);

        // Then, to load the script we need to close the last connection and 
        // connect it again, now specifying the database name
        $conn = \pg_connect($origConnString) or throw new \Exception('Could not connect to postgres');

        $dbFile = file_get_contents(__DIR__ . '/../docs/database/00001_create_db.sql');
        \pg_query($conn, $dbFile) or throw new \Exception('could not run database script');

        \pg_close($conn);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $headers
     * @param array  $cookies
     * @param array  $serverParams
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = [],
        string $body = ''
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $stream = (new StreamFactory())->createStream($body);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
