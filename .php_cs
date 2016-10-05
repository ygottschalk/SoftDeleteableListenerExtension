<?php

return Symfony\CS\Config::create()
    // use default SYMFONY_LEVEL and extra fixers:
    ->fixers(array(
        'combine_consecutive_unsets',
        'header_comment',
        'long_array_syntax',
        'no_useless_else',
        'no_useless_return',
        'ordered_use',
        'php_unit_construct',
        'php_unit_strict',
        'strict',
        'strict_param',
    ))
    ->finder(
        Symfony\CS\Finder::create()
            ->exclude('Symfony/CS/Tests/Fixtures')
            ->in(__DIR__)
    )
;
