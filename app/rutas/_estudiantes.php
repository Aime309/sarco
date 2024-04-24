<?php

use SARCO\Mediadores\AseguraQueElUsuarioEstaAutenticado;
use SARCO\Mediadores\Autorizador;
use SARCOV2\Usuarios\Dominio\Rol;

Flight::group('/estudiantes', function (): void {
  Flight::route('GET /inscribir', function (): void {
    renderizar('inscribir', 'Inscribir estudiante', 'principal');
  });

  Flight::route('GET /', function (): void {
  });
}, [
  AseguraQueElUsuarioEstaAutenticado::class,
  Autorizador::autorizarRoles(Rol::Secretario)
]);
