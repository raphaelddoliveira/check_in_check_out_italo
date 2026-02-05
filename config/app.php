<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

function config(string $key, $default = null)
{
    $map = [
        'app.url'              => $_ENV['APP_URL'] ?? $default,
        'app.env'              => $_ENV['APP_ENV'] ?? 'production',
        'app.debug'            => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
        'espocrm.url'          => $_ENV['ESPOCRM_URL'] ?? $default,
        'espocrm.api_key'      => $_ENV['ESPOCRM_API_KEY'] ?? $default,
        'espocrm.secret_key'   => $_ENV['ESPOCRM_SECRET_KEY'] ?? $default,
        'db.path'              => $_ENV['DB_PATH'] ?? 'database/app.sqlite',
        'token.expiry_hours'   => (int) ($_ENV['TOKEN_EXPIRY_HOURS'] ?? 72),
        'session.lifetime_min' => (int) ($_ENV['SESSION_LIFETIME_MINUTES'] ?? 120),
    ];

    return $map[$key] ?? $default;
}
