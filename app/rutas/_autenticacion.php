<?php

use Leaf\Router;
use SARCO\Controladores\Web\ControladorDeAutenticacion;

Router::all('/salir', function (): void {
  unset($_SESSION['credenciales.cedula']);
});

Router::group('/ingresar', [
  function (): void {
    $controlador = contenedor()->get(ControladorDeAutenticacion::class);

    Router::post('/', [$controlador, 'procesarCredenciales']);
    Router::get('/', [$controlador, 'mostrarIngreso']);
  }
]);
