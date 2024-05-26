<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use Symfony\Component\Uid\UuidV4;

return function (Router $router): void {
  $router->get('/', function (): void {
    $estudiantes = bd()->query("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
      genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
      id_mama as idMama, id_papa as idPapa FROM estudiantes
    ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

    App::render('paginas/estudiantes/listado', compact('estudiantes'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Estudiantes']);
  });

  $router->get('/inscribir', function (): void {
    $periodos = bd()->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC
    ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

    $periodoActual = bd()->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC LIMIT 1
    ")->fetchObject(Periodo::class) ?: null;

    App::render(
      'paginas/estudiantes/inscribir',
      compact('periodos', 'periodoActual'),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Inscribir estudiante']);
  })->addMiddleware(autorizar(Rol::Secretario));

  $router->post('/inscribir', function (): void {
    $inscripcion = App::request()->data->getData();

    bd()->beginTransaction();

    try {
      $sentencia = bd()->prepare("
        INSERT INTO representantes (id, nombres, apellidos, cedula, genero,
        fecha_nacimiento, estado_civil, nacionalidad, telefono, correo)
        VALUES (:id, :nombres, :apellidos, :cedula, :genero, :fechaNacimiento,
        :estadoCivil, :nacionalidad, :telefono, :correo)
      ");

      if (!empty($inscripcion['padre']['nombres'])) {
        $idDelPapa = new UuidV4;
        $sentencia->bindValue(':id', $idDelPapa);
        $sentencia->bindValue(':nombres', $inscripcion['padre']['nombres']);
        $sentencia->bindValue(':apellidos', $inscripcion['padre']['apellidos']);
        $sentencia->bindValue(':cedula', $inscripcion['padre']['cedula'], PDO::PARAM_INT);
        $sentencia->bindValue(':genero', Genero::Masculino->value);
        $sentencia->bindValue(':fechaNacimiento', $inscripcion['padre']['fecha_nacimiento']);
        $sentencia->bindValue(':estadoCivil', $inscripcion['padre']['estado_civil']);
        $sentencia->bindValue(':nacionalidad', $inscripcion['padre']['nacionalidad']);
        $sentencia->bindValue(':telefono', $inscripcion['padre']['telefono']);
        $sentencia->bindValue(':correo', $inscripcion['padre']['correo']);

        $sentencia->execute();
      }

      $idDeLaMama = new UuidV4;
      $sentencia->bindValue(':id', $idDeLaMama);
      $sentencia->bindValue(':nombres', $inscripcion['madre']['nombres']);
      $sentencia->bindValue(':apellidos', $inscripcion['madre']['apellidos']);
      $sentencia->bindValue(':cedula', $inscripcion['madre']['cedula'], PDO::PARAM_INT);
      $sentencia->bindValue(':genero', Genero::Femenino->value);
      $sentencia->bindValue(':fechaNacimiento', $inscripcion['madre']['fecha_nacimiento']);
      $sentencia->bindValue(':estadoCivil', $inscripcion['madre']['estado_civil']);
      $sentencia->bindValue(':nacionalidad', $inscripcion['madre']['nacionalidad']);
      $sentencia->bindValue(':telefono', $inscripcion['madre']['telefono']);
      $sentencia->bindValue(':correo', $inscripcion['madre']['correo']);

      $sentencia->execute();
      $idDelPapa ??= 'NULL';
      [$añoDeNacimiento] = explode('-', $inscripcion['estudiante']['fecha_nacimiento']);
      $ultimosDigitosAñoNacimiento = substr($añoDeNacimiento, 2);
      $cedulaEscolar = "v-1{$ultimosDigitosAñoNacimiento}{$inscripcion['madre']['cedula']}";

      $sentencia = bd()->prepare("
        INSERT INTO estudiantes (id, nombres, apellidos, cedula,
        fecha_nacimiento, lugar_nacimiento, genero, tipo_sangre, id_mama,
        id_papa) VALUES (:id, :nombres, :apellidos, :cedula, :fechaNacimiento,
        :lugarNacimiento, :genero, :grupoSanguineo, '$idDeLaMama', '$idDelPapa')
      ");

      $idDelEstudiante = new UuidV4;
      $sentencia->bindValue(':id', $idDelEstudiante);
      $sentencia->bindValue(':nombres', $inscripcion['estudiante']['nombres']);
      $sentencia->bindValue(':apellidos', $inscripcion['estudiante']['apellidos']);
      $sentencia->bindValue(':cedula', $cedulaEscolar);
      $sentencia->bindValue(':fechaNacimiento', $inscripcion['estudiante']['fecha_nacimiento']);
      $sentencia->bindValue(':lugarNacimiento', $inscripcion['estudiante']['lugar_nacimiento']);
      $sentencia->bindValue(':genero', $inscripcion['estudiante']['genero']);
      $sentencia->bindValue(':grupoSanguineo', $inscripcion['estudiante']['grupo_sanguineo']);
      $sentencia->execute();

      $sentencia = bd()->prepare("
        INSERT INTO inscripciones (id, id_periodo, id_estudiante,
        id_asignacion_sala) VALUES (:id, :idPeriodo, :idEstudiante, :idAsignacion)
      ");

      $sentencia->bindValue(':id', new UuidV4);
      $sentencia->bindValue(':idPeriodo', $inscripcion['id_periodo']);
      $sentencia->bindValue(':idEstudiante', $idDelEstudiante);
      $sentencia->bindValue(':idAsignacion', $inscripcion['id_asignacion_sala']);
      $sentencia->execute();

      $momentos = bd()->query("
        SELECT m.id, numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio,
        mes_cierre as mesCierre,
        dia_cierre as diaCierre,
        m.fecha_registro as fechaRegistro
        FROM momentos m
        JOIN periodos p
        ON id_periodo = p.id
        WHERE id_periodo = '{$inscripcion['id_periodo']}'
        ORDER BY numero
      ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

      $sentencia = bd()->prepare("
        INSERT INTO boletines (id, numero_inasistencias, nombre_proyecto,
        descripcion_formacion, descripcion_ambiente, recomendaciones,
        id_estudiante, id_momento, id_asignacion_sala) VALUES (:id, 0,
        'No especificado', 'No especificada', 'No especificada',
        'No especificadas', :idEstudiante, :idMomento, :idAsignacion)
      ");

      foreach ($momentos as $momento) {
        $sentencia->execute([
          ':id' => new UuidV4,
          ':idEstudiante' => $idDelEstudiante,
          ':idMomento' => $momento->id,
          ':idAsignacion' => $inscripcion['id_asignacion_sala']
        ]);
      }

      bd()->commit();
      $_SESSION['mensajes.exito'] = 'Estudiante inscrito exitósamente';
      App::redirect('/inscripciones');
    } catch (PDOException $error) {
      bd()->rollBack();

      dd($error, $inscripcion);
      throw $error;
      App::redirect('/estudiantes/inscribir');
    }
  })->addMiddleware(autorizar(Rol::Secretario));

  $router->get('/boletines', function (): void {
    $boletines = bd()->query("
      SELECT b.id, numero_inasistencias as inasistencias,
      nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente, recomendaciones,
      b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante, e.cedula as cedulaEstudiante,
      m.numero as momento, b.id_asignacion_sala as idAsignacion
      FROM boletines b
      JOIN estudiantes e
      JOIN momentos m
      ON b.id_estudiante = e.id
      AND b.id_momento = m.id
    ")->fetchAll(PDO::FETCH_CLASS, Boletin::class);

    App::render('paginas/boletines/listado', compact('boletines'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Boletines']);
  })->addMiddleware(Rol::Secretario, Rol::Docente);

  $router->group('/boletines/@id:[0-9]+', function (Router $router): void {
    $router->get('/', function (int $id): void {
      $boletin = bd()->query("
        SELECT b.id, numero_inasistencias as inasistencias,
        nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
        descripcion_ambiente as descripcionAmbiente, recomendaciones,
        b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
        e.apellidos as apellidosEstudiante, e.cedula_escolar as cedulaEstudiante,
        m.numero_momento as momento FROM boletines b JOIN estudiantes e
        JOIN momentos m ON b.id_estudiante = e.id AND b.id_momento = m.id
        WHERE b.id = $id
      ")->fetchObject(Boletin::class);

      App::render('paginas/boletines/editar', compact('boletin'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Editar boletín']);
    });

    $router->post('/', function (int $id): void {
      $boletin = App::request()->data->getData();

      $sentencia = bd()->prepare("
        UPDATE boletines SET numero_inasistencias = :inasistencias,
        nombre_proyecto = :proyecto, descripcion_formacion = :formacion,
        descripcion_ambiente = :ambiente, recomendaciones = :recomendaciones
        WHERE id = $id
      ");

      $sentencia->bindValue(':inasistencias', $boletin['inasistencias'], PDO::PARAM_INT);
      $sentencia->bindValue(':proyecto', $boletin['proyecto']);
      $sentencia->bindValue(':formacion', $boletin['formacion']);
      $sentencia->bindValue(':ambiente', $boletin['ambiente']);
      $sentencia->bindValue(':recomendaciones', $boletin['recomendaciones']);

      try {
        $sentencia->execute();
        $_SESSION['mensajes.exito'] = 'Boletín actualizado exitósamente';
        App::redirect('/estudiantes/boletines');
      } catch (PDOException $error) {
        throw $error;
      }
    });
  }, [autorizar(Rol::Docente)]);
};
