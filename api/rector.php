<?php

declare(strict_types = 1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;
use Rector\Set\ValueObject\LevelSetList;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\Packages\Faker\FakerSetList;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use RectorLaravel\Rector\ArrayDimFetch\EnvVariableToEnvHelperRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/public',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->removeUnusedImports();

    // tests/bootstrap.php intentionally writes to the $_ENV superglobal before
    // the framework boots so Laravel's immutable env repository can read the
    // testing values. This rule rewrites those writes to Env::get($key) = ...,
    // which is invalid PHP (assigning to a method return value), so skip it there.
    $rectorConfig->skip([
        EnvVariableToEnvHelperRector::class => [
            __DIR__ . '/tests/bootstrap.php',
        ],
    ]);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_85,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
        SetList::CODING_STYLE,

        // Laravel specific
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelLevelSetList::UP_TO_LARAVEL_130,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_ARRAYACCESS_TO_METHOD_CALL,
        LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        FakerSetList::FAKER_10,
    ]);

    // Ensure file system caching is used instead of in-memory.
    $rectorConfig->cacheClass(FileCacheStorage::class);

    // Specify a path that works locally as well as on CI job runners.
    $rectorConfig->cacheDirectory('./storage/app/rector');

    $rectorConfig->parallel(300);
};
