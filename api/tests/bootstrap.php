<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

/*
 * The Docker `api` service injects runtime values such as APP_ENV=local and
 * DB_CONNECTION=pgsql as real OS environment variables, which PHP exposes in
 * $_SERVER. Laravel's env repository is immutable and reads $_SERVER first, so
 * neither phpunit.xml <env> entries nor .env are able to override them. Pin the
 * testing-critical variables here, before the framework boots, so the suite
 * always runs in the testing environment against the isolated test database.
 */
foreach ([
    'APP_ENV' => 'testing',
    'DB_CONNECTION' => 'pgsql-test',
] as $key => $value) {
    \Illuminate\Support\Env::get($key)    = $value;
    $_SERVER[$key] = $value;
    putenv(sprintf('%s=%s', $key, $value));
}
