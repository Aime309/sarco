<?php

use Leaf\Router;
use SARCO\Mediadores\ManejadorDeMensajes;

foreach (glob(__DIR__ . '/_*.php') as $definicion) {
  require $definicion;
}

Router::set404(function (): void {
  ManejadorDeMensajes::capturarMensajes();
  renderizar('404', '404 ~ No encontrado', 'errores');
});
