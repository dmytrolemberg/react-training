<?php

declare(strict_types = 1);
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return
    new Config()
        ->setRiskyAllowed(true)
        ->setCacheFile(__DIR__ . '/storage/app/.php-cs-fixer.cache')
        ->setFinder(
            Finder::create()
                ->in(__DIR__)
                ->ignoreDotFiles(true)
                ->ignoreVCS(true)
                ->ignoreVCSIgnored(true)
                ->ignoreUnreadableDirs()
                ->files()
                ->name('*.php')
                ->exclude([ // exclude folders
                    'bootstrap/cache',
                    'node_modules',
                    'public',
                    'storage',
                    'vendor',
                ])
                ->notName('*.blade.php') // Blade templates are matched by *.php
                ->notName([ // generated IDE helper files
                    '_ide_helper.php',
                    '_ide_helper_models.php',
                    '.phpstorm.meta.php',
                ])
                ->append([
                    __FILE__,
                ]),
        )
        ->setRules([
            '@PSR12' => true,
            '@PSR12:risky' => true,
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,
            '@Symfony' => true,
            '@Symfony:risky' => true,
            '@PHP8x2Migration:risky' => true,
            '@PHP8x4Migration' => true,
            '@PHPUnit10x0Migration:risky' => true,
            '@PER-CS3x0' => true,
            '@PER-CS3x0:risky' => true,

            'static_lambda' => false,
            'native_function_invocation' => false,
            'fopen_flags' => ['b_mode' => true],
            'non_printable_character' => ['use_escape_sequences_in_strings' => false],

            'ordered_imports' => ['sort_algorithm' => 'length'],
            'fully_qualified_strict_types' => [
                'import_symbols' => false,
                'leading_backslash_in_global_namespace' => true,
            ],

            'concat_space' => ['spacing' => 'one'],
            'binary_operator_spaces' => false,
            'declare_equal_normalize' => ['space' => 'single'],

            'phpdoc_to_comment' => false,
            'phpdoc_separation' => false,
            'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
            'phpdoc_align' => false,

            'operator_linebreak' => false,

            'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],

            'php_unit_strict' => false,
            'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
            'php_unit_internal_class' => false,
            'php_unit_test_class_requires_covers' => false,
            'php_unit_data_provider_name' => false,
            'php_unit_data_provider_method_order' => false,
            'php_unit_data_provider_return_type' => false,

            'yoda_style' => false,

            'final_internal_class' => false,
            'octal_notation' => false,
            'increment_style'=> false,

            'declare_strict_types' => true,
        ]);
