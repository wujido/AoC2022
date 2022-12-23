<?php

use App\{App, Autoloader};
use Util\DotEnv;

require_once 'App/Autoloader.php';
require_once 'Util/functions.php';

error_reporting(0);

Autoloader::register();

(new DotEnv(__DIR__ . '/.env'))->load();

$app = new App();
$app->run();


