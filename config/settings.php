<?php
/**
 * General settings.
 * Mode - operating mode.(production/developer)
 * Determines whether or not to display errors on the screen.
 *
 * 'logDir' - set filepath to log file.
 */

return [
    'mode' => \tt\Core::DEPLOY_MODE_DEVELOPER,
    'logDir' => 'var/log'
];
