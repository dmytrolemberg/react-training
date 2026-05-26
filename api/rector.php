<?php

declare(strict_types = 1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetList;
use Rector\Set\ValueObject\LevelSetList;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\Packages\Faker\FakerSetList;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;

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
