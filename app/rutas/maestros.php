<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Modelos\Usuario;

return function (Router $router): void {
  $router->get('/', function (): void {
    $idAutenticado = App::view()->get('usuario')->id;

    $maestros = bd()->query("
      SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
      direccion, telefono, correo, rol, esta_activo as estaActivo,
      fecha_registro as fechaRegistro
      FROM usuarios WHERE rol = 'Docente' AND id != '$idAutenticado'
    ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

    App::render('paginas/maestros/listado', compact('maestros'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Maestros']);
  });
};
