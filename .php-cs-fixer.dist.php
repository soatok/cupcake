<?php
declare(strict_types=1);

use Soatok\CodeStyle\SoatokRules;

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
;

return SoatokRules::config()->setFinder($finder);
