<?php

use App\Dispatcher;

require_once "../src/app/bootstrap.php";
//require '../src/app/Dispatcher.php';
header("Content-Type: application/json; charset UTF-8");
define ('SITE_ROOT', realpath(dirname(__FILE__)));
$app = new Dispatcher();
$app->dispatch();