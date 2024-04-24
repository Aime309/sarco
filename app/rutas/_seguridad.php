<?php

use SARCO\Controladores\Web\ControladorDeSeguridad;
use SARCO\Mediadores\AseguraQueElUsuarioEstaAutenticado;
use SARCO\Mediadores\Autorizador;
use SARCOV2\Usuarios\Dominio\Rol;

Flight::group('/', function (): void {
  Flight::route('/respaldar', [ControladorDeSeguridad::class, 'respaldar']);
  Flight::route('/restaurar', [ControladorDeSeguridad::class, 'restaurar']);
}, [
  AseguraQueElUsuarioEstaAutenticado::class,
  Autorizador::autorizarRoles(Rol::Director, Rol::Secretario)
]);
