<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Modelos\Inscripcion;

return function (Router $router): void {
  $router->get('/', function (): void {
    $inscripciones = bd()->query("
      SELECT i.id, i.fecha_registro as fechaRegistro,
      p.anio_inicio as periodo, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante
      FROM inscripciones i JOIN periodos p JOIN estudiantes e
      JOIN asignaciones_de_salas a ON i.id_periodo = p.id AND i.id_estudiante = e.id
      AND i.id_asignacion_sala = a.id
    ")->fetchAll(PDO::FETCH_CLASS, Inscripcion::class);

    App::render(
      'paginas/inscripciones/listado',
      compact('inscripciones'),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Inscripciones']);
  });
};
