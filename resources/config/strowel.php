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
    | Input:  [Block def] Torrent title (v2) [quality] {provider}
    | Output: ['Block def', 'v2', 'quality', 'provider']
    |
    | WARNING:
    | The current version only supports single character delimiters.
    |
    */

    'block_definitions' => [
        ['[', ']'],
        ['(', ')'],
        ['{', '}'],
    ],

];
