<?php
require_once 'vendor/autoload.php';

use Api\Controllers\PageController;
use Api\Helpers\Services;
use Dotenv\Dotenv;

$dotEnv = Dotenv::createImmutable(__DIR__);
$dotEnv->safeLoad();

$htmlRequest = new PageController(Services::login());
$htmlRequest->init();