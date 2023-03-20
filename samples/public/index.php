<?php
require_once('./vendor/autoload.php');

use Micronative\MockServer\Server;

$dir = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'config/';
$server = new Server([$dir . 'product-api.yml', $dir . 'user-api.yml']);
$server->run();