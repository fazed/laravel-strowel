<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Block Definitions
    |--------------------------------------------------------------------------
    |
    | This list contains block definitions which will be
    | divided into separate blocks of the give title.
    |
    | Example using default definitions:
    | Input:  [Block def] Torrent title (v2) [quality] {provider} ==group==
    | Output: ['Block def', 'v2', 'quality', 'provider', group]
    |
    */

    'block_definitions' => [
        ['[', ']'],
        ['(', ')'],
        ['{', '}'],
        ['==', '=='],
    ],

    /*
    |--------------------------------------------------------------------------
    | Real Title Regex
    |--------------------------------------------------------------------------
    |
    | The regex will be used to determine the actual content
    | the interpreter will be looking for. This is also
    | to ensure the correct data is excluded from the
    | block definition parsing (declared above).
    |
    | When no regex is provided, the parser will return
    | the torrent title excluding block definitions.
    |
    */

    'real_title_regex' => null,

];
