<?php

use SARCO\App;

const ROOT = __DIR__;

try {
  @require __DIR__ . '/vendor/autoload.php';
} catch (Error) {
  exit(<<<html
  Falla al cargar el archivo
  <code>/vendor/autoload.php</code>.
  Ejecuta el comando
  <code>composer install</code>
  html);
}

require_once __DIR__ . '/app/configuraciones/variables de entorno.php';
require_once __DIR__ . '/app/configuraciones/fechas.php';
require_once __DIR__ . '/app/configuraciones/errores.php';
require_once __DIR__ . '/app/configuraciones/vistas.php';
require_once __DIR__ . '/app/configuraciones/sesion.php';
require_once __DIR__ . '/app/configuraciones/base de datos.php';
require_once __DIR__ . '/app/configuraciones/dependencias.php';
require_once __DIR__ . '/app/utilidades.php';
require_once __DIR__ . '/app/rutas.php';

App::start();
