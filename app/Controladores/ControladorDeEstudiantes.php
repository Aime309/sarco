<?php

namespace SARCO\Controladores;

use PDO;
use SARCO\App;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Representante;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Usuario;

final readonly class ControladorDeEstudiantes {
  function __construct(private PDO $pdo) {
  }

  function mostrarListado(): void {
    if (key_exists('cedula', $_GET)) {
      App::redirect("/estudiantes/{$_GET['cedula']}");

      return;
    }

    $estudiantes = $this->pdo->query("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
      genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
      id_mama as idMama, id_papa as idPapa FROM estudiantes
    ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

    App::render('paginas/estudiantes/listado', compact('estudiantes'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Estudiantes']);
  }

  function mostrarPerfil(string $cedula): void {
    $estudiante = $this->pdo->query("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
      genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
      id_mama as idMama, id_papa as idPapa FROM estudiantes
      WHERE cedula = '$cedula'
    ")->fetchObject(Estudiante::class);

    assert($estudiante instanceof Estudiante);

    $mama = $this->pdo->query("
      SELECT id, fecha_registro as fechaRegistro, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, genero, telefono, correo,
      estado_civil as estadoCivil, nacionalidad FROM representantes
      WHERE id = '$estudiante->idMama'
    ")->fetchObject(Representante::class);

    assert($mama instanceof Representante);
    $papa = null;

    if ($estudiante->idPapa) {
      $papa = $this->pdo->query("
        SELECT id, fecha_registro as fechaRegistro, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, genero, telefono, correo,
        estado_civil as estadoCivil, nacionalidad FROM representantes
        WHERE id = '$estudiante->idPapa'
      ")->fetchObject(Representante::class);

      assert($papa instanceof Representante);
    }

    $estudiante->asignarRepresentantes($mama, $papa);

    $inscripciones = $this->pdo->query("
      SELECT * FROM inscripciones i JOIN periodos p JOIN asignaciones_de_salas a
      ON i.id_periodo = p.id AND i.id_asignacion_sala = a.id
      WHERE id_estudiante = '$estudiante->id'
    ")->fetchAll();

    $informacionAcademica = [];

    foreach ($inscripciones as $inscripcion) {
      $boletines = $this->pdo->query("
        SELECT b.id, numero_inasistencias as inasistencias,
        nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
        descripcion_ambiente as descripcionAmbiente, recomendaciones,
        b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
        e.apellidos as apellidosEstudiante, e.cedula as cedulaEstudiante,
        m.numero as numeroMomento, b.id_asignacion_sala as idAsignacion
        FROM boletines b
        JOIN estudiantes e
        JOIN momentos m
        ON b.id_estudiante = e.id
        AND b.id_momento = m.id
        WHERE b.id_estudiante = '$estudiante->id'
        AND m.id_periodo = '{$inscripcion['id_periodo']}'
        ORDER BY numeroMomento
      ")->fetchAll(PDO::FETCH_CLASS, Boletin::class);

      $sala = bd()->query("
        SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
        WHERE id = '{$inscripcion['id_sala']}'
      ")->fetchObject(Sala::class);

      $aula = $this->pdo->query("
        SELECT id, fecha_registro as fechaRegistro, codigo, tipo
        FROM aulas WHERE id = '{$inscripcion['id_aula']}' ORDER BY tipo
      ")->fetchObject(Aula::class);

      $inscripcion['id_docente3'] ??= null;

      $docentes = $this->pdo->query("
        SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
        direccion, telefono, correo, rol, esta_activo as estaActivo,
        fecha_registro as fechaRegistro, clave, genero
        FROM usuarios
        WHERE id IN (
          '{$inscripcion['id_docente1']}',
          '{$inscripcion['id_docente2']}',
          '{$inscripcion['id_docente3']}'
        )
      ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

      $periodo = $inscripcion['anio_inicio'] . '-' . ($inscripcion['anio_inicio'] + 1);
      $informacionAcademica[$periodo] = compact(
        'boletines',
        'sala',
        'aula',
        'docentes'
      );
    }

    App::render('paginas/estudiantes/perfil', compact(
      'estudiante',
      'informacionAcademica'
    ), 'pagina');
    App::render('plantillas/privada', ['titulo' => $estudiante->nombreCompleto()]);
  }
}
