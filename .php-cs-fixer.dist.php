<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/examples'])
    ->notPath('#^src/\.*#');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS3x0' => true,
        '@PHP84Migration' => true,
        'declare_strict_types' => true,
        'strict_param' => true,
        'native_function_invocation' => ['include' => ['@compiler_optimized'], 'scope' => 'namespaced'],
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'global_namespace_import' => false,
    ])
    ->setFinder($finder);
