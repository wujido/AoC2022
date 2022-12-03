<?php

use App\{App, Autoloader};

require_once 'App/Autoloader.php';
require_once 'Util/functions.php';

Autoloader::register();
$app = new App();
$app->run();


