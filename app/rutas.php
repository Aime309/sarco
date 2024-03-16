<?php

use Leaf\Router;
use SARCO\Middlewares\Autenticacion;

Router::group('/', ['middleware' => [Autenticacion::class, 'bloquearNoAutenticados'], function (): void {
  Router::get('/', function (): void {
  });
}]);
