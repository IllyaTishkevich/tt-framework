<?php
/**
 * This page setting using in ALL pages.
 *
 * 'layout' - indicates the path to the file relative to pub/layout.
 *
 * 'source' - Array of connected resources. js and css respectively.
 * 'string' - the string will be inserted in full without processing.
 */

return [
    'layout' => 'default',
    'html_doc' => true,
    'source' => [
        'js' => [],
        'css' => [
            'http://site.loc/css/test.css'
        ],
        'strings' => [
            '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">'
        ]
    ],
];