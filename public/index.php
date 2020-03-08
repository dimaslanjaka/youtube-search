<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require __DIR__ . '/../vendor/autoload.php';

use Curl\YTSRouter;

YTSRouter::setDir(__DIR__);
YTSRouter::start();
