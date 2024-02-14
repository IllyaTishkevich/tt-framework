<?php
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');
session_start();

define("BASE_DIR", __DIR__.'/../');

require './../vendor/autoload.php';
require './../vendor/ttframework/lib/coreFunction.php';

new \tt\ErrorHandler();
$core = new \tt\Core();
$core->execute();
