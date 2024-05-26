<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Representante;
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
        Representante::asegurarValidez($inscripcion['padre']);

        $sentencia->execute([
          ':id' => $idDelPapa,
          ':nombres' => mb_convert_case($inscripcion['padre']['nombres'], MB_CASE_TITLE),
          ':apellidos' => mb_convert_case($inscripcion['padre']['apellidos'], MB_CASE_TITLE),
          ':cedula' => $inscripcion['padre']['cedula'],
          ':genero' => Genero::Masculino->value,
          ':fechaNacimiento' => $inscripcion['padre']['fecha_nacimiento'],
          ':estadoCivil' => ucfirst($inscripcion['padre']['estado_civil']),
          ':nacionalidad' => ucfirst($inscripcion['padre']['nacionalidad']),
          ':telefono' => $inscripcion['padre']['telefono'],
          ':correo' => $inscripcion['padre']['correo']
        ]);
      }

      $idDeLaMama = new UuidV4;
      Representante::asegurarValidez($inscripcion['madre']);

      $sentencia->execute([
        ':id' => $idDeLaMama,
        ':nombres' => mb_convert_case($inscripcion['madre']['nombres'], MB_CASE_TITLE),
        ':apellidos' => mb_convert_case($inscripcion['madre']['apellidos'], MB_CASE_TITLE),
        ':cedula' => $inscripcion['madre']['cedula'],
        ':genero' => Genero::Femenino->value,
        ':fechaNacimiento' => $inscripcion['madre']['fecha_nacimiento'],
        ':estadoCivil' => ucfirst($inscripcion['madre']['estado_civil']),
        ':nacionalidad' => ucfirst($inscripcion['madre']['nacionalidad']),
        ':telefono' => $inscripcion['madre']['telefono'],
        ':correo' => $inscripcion['madre']['correo']
      ]);

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
      Estudiante::asegurarValidez($inscripcion['estudiante']);

      $sentencia->execute([
        ':id' => $idDelEstudiante,
        ':nombres' => mb_convert_case($inscripcion['estudiante']['nombres'], MB_CASE_TITLE),
        ':apellidos' => mb_convert_case($inscripcion['estudiante']['apellidos'], MB_CASE_TITLE),
        ':cedula' => $cedulaEscolar,
        ':fechaNacimiento' => $inscripcion['estudiante']['fecha_nacimiento'],
        ':lugarNacimiento' => mb_convert_case($inscripcion['estudiante']['lugar_nacimiento'], MB_CASE_TITLE),
        ':genero' => ucfirst($inscripcion['estudiante']['genero']),
        ':grupoSanguineo' => strtoupper($inscripcion['estudiante']['grupo_sanguineo']),
      ]);

      $sentencia = bd()->prepare("
        INSERT INTO inscripciones (id, id_periodo, id_estudiante,
        id_asignacion_sala) VALUES (:id, :idPeriodo, :idEstudiante, :idAsignacion)
      ");

      $sentencia->execute([
        ':id' => new UuidV4,
        ':idPeriodo' => $inscripcion['id_periodo'],
        ':idEstudiante' => $idDelEstudiante,
        ':idAsignacion' => $inscripcion['id_asignacion_sala'],
      ]);

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
      unset($_SESSION['datos']);
      exit(App::redirect('/inscripciones'));
    } catch (PDOException $error) {
      bd()->rollBack();

      throw $error;
    } catch (InvalidArgumentException $error) {
      $_SESSION['mensajes.error'] = $error->getMessage();
    }

    $_SESSION['datos'] = $inscripcion;
    App::redirect(App::request()->referrer);
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
