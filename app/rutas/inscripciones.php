<?php

use flight\net\Router;
use SARCO\Controladores\ControladorDeInscripciones;

return function (Router $router): void {
  $router->get('/', [ControladorDeInscripciones::class, 'indice']);
};
