<?php
/**
 * Array of routes.
 * Key - matches url.
 * Value is started by the controller itself.
 */

return [
    '404' => 'tt\controllers\NotFound',
    'test' => 'app\controllers\Test',
    '' => 'app\controllers\Homepage'
];