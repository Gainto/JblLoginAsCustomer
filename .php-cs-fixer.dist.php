<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.0.0-rc.1|configurator
 * you can change this configuration by importing this file.
 */
return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PHP74Migration' => true,
        'blank_line_after_opening_tag' => false,
        'combine_consecutive_issets' => false,
        'combine_consecutive_unsets' => false,
        'concat_space' => ['spacing'=>'one'],
        'increment_style' => ['style'=>'post'],
        'linebreak_after_opening_tag' => false,
        'mb_str_functions' => true,
        'native_function_invocation' => false,
        'nullable_type_declaration_for_default_null_value' => true,
        'phpdoc_align' => ['align'=>'left'],
        'phpdoc_summary' => false,
        'single_line_throw' => false,
        'strict_comparison' => true,
        'strict_param' => true,
        'use_arrow_functions' => false,
        'yoda_style' => false,
        'phpdoc_to_comment' => false,
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => false
        ],
        'php_unit_test_class_requires_covers' => false
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->in(__DIR__)
    )
    ;
