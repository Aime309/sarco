<?php

use SARCO\App;

const ROOT = __DIR__;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/configuraciones/variables de entorno.php';
require __DIR__ . '/app/configuraciones/fechas.php';
require __DIR__ . '/app/configuraciones/errores.php';
require __DIR__ . '/app/configuraciones/vistas.php';
require __DIR__ . '/app/configuraciones/sesion.php';
require __DIR__ . '/app/configuraciones/base de datos.php';
require __DIR__ . '/app/rutas.php';

App::start();
