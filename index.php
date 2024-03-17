<?php

use Leaf\Router;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/configuracion.php';
require __DIR__ . '/app/ui.php';
require __DIR__ . '/app/rutas.php';

Router::run();
