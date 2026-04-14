<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'node_modules'])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,

        // 🔥 Reglas PRO adicionales
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'no_extra_blank_lines' => true,
        'no_trailing_whitespace' => true,
        'single_quote' => true,
        'binary_operator_spaces' => [
            'default' => 'align_single_space_minimal'
        ],
        'blank_line_before_statement' => [
            'statements' => ['return']
        ],
        'no_superfluous_phpdoc_tags' => true,
        'ordered_imports' => true,
        'phpdoc_align' => true,
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setFinder($finder);