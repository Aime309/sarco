<?php

use Leaf\Router;
use SARCO\Controladores\Web\ControladorDeUsuarios;
use SARCO\Mediadores\AseguradorDeQueNoHayDirectoresActivos;
use SARCO\Mediadores\ManejadorDeMensajes;

Router::group('/registrate', [
  'middleware' => function (): void {
    ManejadorDeMensajes::capturarMensajes();
    contenedor()->get(AseguradorDeQueNoHayDirectoresActivos::class);
  },
  function (): void {
    $controladorDeUsuarios = contenedor()->get(ControladorDeUsuarios::class);

    Router::get('/', [$controladorDeUsuarios, 'mostrarRegistroDirector']);
    Router::post('/', [$controladorDeUsuarios, 'registrarDirector']);
  }
]);
