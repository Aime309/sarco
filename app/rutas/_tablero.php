<?php

use SARCO\Controladores\Web\ControladorDelDelPanelPrincipal;
use SARCO\Mediadores\AseguraQueElUsuarioEstaAutenticado;

Flight::group('/', function (): void {
  Flight::route('GET /', [ControladorDelDelPanelPrincipal::class, 'mostrarPaginaDeInicio']);

  Flight::route('GET /maestros', function (): void {
  });

  Flight::route('GET /usuarios', function (): void {
  });

  Flight::route('GET /periodos', function (): void {
  });

  Flight::route('GET /momentos', function (): void {
  });

  Flight::route('GET /salas', function (): void {
  });

  Flight::route('GET /salas/registrar', function (): void {
  });

  Flight::route('GET /momentos/registrar', function (): void {
  });

  Flight::route('GET /periodos/registrar', function (): void {
  });

  Flight::route('GET /usuarios/registrar', function (): void {
  });

  Flight::route('GET /maestros/registrar', function (): void {
  });

  Flight::route('GET /asignar', function (): void {
    renderizar('asignaciones', 'Asignar estudiante', 'principal');
  });

  Flight::post('/asignar', function (): void {
  });
}, [AseguraQueElUsuarioEstaAutenticado::class]);
