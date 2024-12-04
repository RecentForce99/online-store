<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        'src',
        'tests',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        'global_namespace_import' => true,
        'single_line_comment_style' => false,
        'braces_position' => [
            'allow_single_line_anonymous_functions' => true,
            'allow_single_line_empty_anonymous_classes' => true,
        ],
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'next',
        ],
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'no_useless_else' => true,
        'single_line_throw' => false,
        'cast_spaces' => [
            'space' => 'none',
        ],
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'only_if_meta',
                'method' => 'one',
                'property' => 'only_if_meta',
                'trait_import' => 'none',
                'case' => 'none',
            ],
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
    ])
    ->setFinder($finder);
