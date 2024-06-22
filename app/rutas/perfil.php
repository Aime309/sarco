<?php

use flight\net\Router;
use SARCO\Controladores\ControladorDePerfil;

return function (Router $router): void {
  $router->get('/', [ControladorDePerfil::class, 'mostrarFormularioDeEdicion']);
  $router->post('/', [ControladorDePerfil::class, 'actualizarPerfil']);
  $router->post('/actualizar-clave', [ControladorDePerfil::class, 'actualizarClave']);
  $router->post('/desactivar', [ControladorDePerfil::class, 'desactivarPerfil']);
};
