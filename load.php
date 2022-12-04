<?php

use App\{App, Autoloader};
use Util\ContentLoader;

require_once 'App/Autoloader.php';
require_once 'Util/functions.php';

Autoloader::register();

$day = getVal('day');
if (is_null($day))
    exit;

$contentLoader = new ContentLoader();

echo $contentLoader->loadTask($day);
