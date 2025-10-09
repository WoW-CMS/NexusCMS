<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/app')
    ->in(__DIR__.'/config')
    ->in(__DIR__.'/database')
    ->in(__DIR__.'/resources')
    ->in(__DIR__.'/routes')
    ->in(__DIR__.'/tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'single_blank_line_at_eof' => true,
    ])
    ->setFinder($finder);