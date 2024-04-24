<?php

use SARCO\Controladores\Web\ControladorDeRepresentantes;
use SARCO\Mediadores\AseguradorQueElUsuarioEstaAutenticado;
use SARCO\Mediadores\Autorizador;
use SARCOV2\Usuarios\Dominio\Rol;

Flight::group('/representantes', function (): void {
  Flight::route('GET /', [ControladorDeRepresentantes::class, 'mostrarListado']);
  Flight::route('GET /nuevo', [ControladorDeRepresentantes::class, 'mostrarRegistro']);
  Flight::route('POST /nuevo', [ControladorDeRepresentantes::class, 'procesarRegistro']);
  Flight::group('/@cedula', function (): void {
    Flight::route('GET /editar', [ControladorDeRepresentantes::class, 'mostrarEdicion']);
    Flight::route('POST /editar', [ControladorDeRepresentantes::class, 'procesarEdicion']);
  });
}, [
  AseguradorQueElUsuarioEstaAutenticado::class,
  Autorizador::autorizarRoles(Rol::Secretario)
]);
