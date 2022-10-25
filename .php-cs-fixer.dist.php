<?php

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(['src'])
            ->files()
            ->name('*.php')
    )
;
