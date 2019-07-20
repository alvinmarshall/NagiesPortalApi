<?php


use Dotenv\Dotenv;

require __DIR__. '/../../vendor/autoload.php';
$env = Dotenv::create(__DIR__. '/../../');
$env->load();