<?php

use SARCO\Controladores\Web\ControladorDeAutenticacion;

Flight::route('/salir', function (): void {
  unset($_SESSION['credenciales.cedula']);
  Flight::redirect('/');
});

Flight::group('/ingresar', function (): void {
  Flight::route('POST /', [ControladorDeAutenticacion::class, 'procesarCredenciales']);
  Flight::route('GET /', [ControladorDeAutenticacion::class, 'mostrarIngreso']);
}, [
  function () {
    if (!empty($_SESSION['credenciales.cedula'])) {
      exit(Flight::redirect('/'));
    }
  }
]);
