<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createMutable(__DIR__);
$dotenv->load();
$dotenv->required(['DATABASE_URL', 'SECRET']);
