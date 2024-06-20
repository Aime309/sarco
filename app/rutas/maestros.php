<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Inscripcion;
use SARCO\Modelos\Maestro;
use SARCO\Modelos\Sala;

return function (Router $router): void {
  $router->get('/', function (): void {
    $idAutenticado = App::view()->get('usuario')->id;
    $rol = Rol::Docente->value;

    $maestros = bd()->query("
      SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
      direccion, telefono, correo, rol, esta_activo as estaActivo,
      fecha_registro as fechaRegistro
      FROM usuarios WHERE rol = '$rol' AND id != '$idAutenticado'
    ")->fetchAll(PDO::FETCH_CLASS, Maestro::class);

    App::render('paginas/maestros/listado', compact('maestros'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Maestros']);
  });

  $router->get('/@cedula', function (string $cedula): void {
    $rol = Rol::Docente->value;

    $maestro = bd()->query("
      SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
      direccion, telefono, correo, rol, esta_activo as estaActivo,
      fecha_registro as fechaRegistro
      FROM usuarios WHERE rol = '$rol' AND cedula = '$cedula'
    ")->fetchObject(Maestro::class);

    $asignaciones = bd()->query("
      SELECT a.id, id_sala, id_aula, id_periodo, id_docente1, id_docente2,
      id_docente3, p.anio_inicio
      FROM asignaciones_de_salas a
      JOIN periodos p
      ON a.id_periodo = p.id
      WHERE id_docente1 = '$maestro->id'
      OR id_docente2 = '$maestro->id'
      OR id_docente3 = '$maestro->id'
    ")->fetchAll(PDO::FETCH_ASSOC);

    $informacionLaboral = [];

    foreach ($asignaciones as $asignacion) {
      $sala = bd()->query("
        SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
        WHERE id = '{$asignacion['id_sala']}'
      ")->fetchObject(Sala::class);

      $aula = bd()->query("
        SELECT id, fecha_registro as fechaRegistro, codigo, tipo
        FROM aulas WHERE id = '{$asignacion['id_aula']}' ORDER BY tipo
      ")->fetchObject(Aula::class);

      $compañeros = bd()->query("
        SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
        direccion, telefono, correo, rol, esta_activo as estaActivo,
        fecha_registro as fechaRegistro, clave, genero
        FROM usuarios
        WHERE id IN (
          '{$asignacion['id_docente1']}',
          '{$asignacion['id_docente2']}',
          '{$asignacion['id_docente3']}'
        ) AND id != '$maestro->id'
      ")->fetchAll(PDO::FETCH_CLASS, Maestro::class);

      $periodo = $asignacion['anio_inicio'] . '-' . ($asignacion['anio_inicio'] + 1);

      $inscripciones = bd()->query("
        SELECT i.id, i.fecha_registro as fechaRegistro,
        e.nombres as nombresEstudiante, e.apellidos as apellidosEstudiante,
        e.cedula as cedulaEstudiante,
        e.fecha_nacimiento as fechaNacimientoEstudiante,
        p.anio_inicio as periodo
        FROM inscripciones i
        JOIN estudiantes e
        JOIN periodos p
        ON i.id_estudiante = e.id
        AND i.id_periodo = p.id
        WHERE id_asignacion_sala = '{$asignacion['id']}'
      ")->fetchAll(PDO::FETCH_CLASS, Inscripcion::class);

      $informacionLaboral[$periodo] = compact(
        'sala',
        'aula',
        'compañeros',
        'inscripciones'
      );
    }

    App::render('paginas/maestros/perfil', compact(
      'maestro',
      'informacionLaboral'
    ), 'pagina');
    App::render('plantillas/privada', ['titulo' => $maestro->nombreCompleto()]);
  });
};
