<?php

use Illuminate\Container\Container;
use SARCO\Controladores\Web\ControladorDeUsuarios;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\Rol;

Flight::group('/registrate', function (): void {
  Flight::route('GET /', [ControladorDeUsuarios::class, 'mostrarRegistroDirector']);
  Flight::route('POST /', [ControladorDeUsuarios::class, 'registrarDirector']);
}, [
  function (): void {
    $directores = Container::getInstance()
      ->get(RepositorioDeUsuarios::class)
      ->obtenerTodosPorRol(Rol::Director);

    if ($directores->hayActivos()) {
      $_SESSION['error'] = 'Ya hay al menos 1 director activo';
      Flight::redirect('/');
    }
  }
]);
