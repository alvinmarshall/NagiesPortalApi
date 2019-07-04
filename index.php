<?php /** @noinspection PhpIncludeInspection */

use App\Dispatcher;

require_once /** @lang text */
"./src/app/bootstrap.php";
header("Content-Type: application/json; charset UTF-8");
$app = new Dispatcher();
$app->dispatch();