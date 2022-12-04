<?php

use App\{App, Autoloader};
use Util\DotEnv;

require_once 'App/Autoloader.php';
require_once 'Util/functions.php';

Autoloader::register();

(new DotEnv(__DIR__ . '/.env'))->load();

$app = new App();
$app->run();


