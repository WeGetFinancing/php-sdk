<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PHP80Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'no_php4_constructor' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'phpdoc_order' => true,
        'phpdoc_order_by_value' => true,
        'strict_comparison' => true,
        'strict_param' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
