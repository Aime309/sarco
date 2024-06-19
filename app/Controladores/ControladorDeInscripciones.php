<?php

namespace SARCO\Controladores;

use InvalidArgumentException;
use PDO;
use PDOException;
use SARCO\App;
use SARCO\Enumeraciones\Genero;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Inscripcion;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Representante;
use Symfony\Component\Uid\UuidV4;

final readonly class ControladorDeInscripciones {
  function __construct(private PDO $pdo) {
  }

  function mostrarListado(): void {
    $inscripciones = $this->pdo->query("
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
  }

  function mostrarFormularioDeInscripcion(): void {
    $periodos = $this->pdo->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC
    ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

    $periodoActual = $this->pdo->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC LIMIT 1
    ")->fetchObject(Periodo::class) ?: null;

    App::render(
      'paginas/estudiantes/inscribir',
      compact('periodos', 'periodoActual'),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Inscribir estudiante']);
  }

  function inscribir(): void {
    $inscripcion = App::request()->data->getData();

    $this->pdo->beginTransaction();

    try {
      $sentencia = $this->pdo->prepare("
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

      $idDelPapa ??= null;
      [$añoDeNacimiento] = explode('-', $inscripcion['estudiante']['fecha_nacimiento']);
      $ultimosDigitosAñoNacimiento = substr($añoDeNacimiento, 2);
      $cedulaEscolar = "v-1{$ultimosDigitosAñoNacimiento}{$inscripcion['madre']['cedula']}";

      $sentencia = $this->pdo->prepare("
        INSERT INTO estudiantes (id, nombres, apellidos, cedula,
        fecha_nacimiento, lugar_nacimiento, genero, tipo_sangre, id_mama,
        id_papa) VALUES (:id, :nombres, :apellidos, :cedula, :fechaNacimiento,
        :lugarNacimiento, :genero, :grupoSanguineo, '$idDeLaMama', :idDelPapa)
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
        ':idDelPapa' => $idDelPapa
      ]);

      $sentencia = $this->pdo->prepare("
        INSERT INTO inscripciones (id, id_periodo, id_estudiante,
        id_asignacion_sala) VALUES (:id, :idPeriodo, :idEstudiante, :idAsignacion)
      ");

      $sentencia->execute([
        ':id' => new UuidV4,
        ':idPeriodo' => $inscripcion['id_periodo'],
        ':idEstudiante' => $idDelEstudiante,
        ':idAsignacion' => $inscripcion['id_asignacion_sala'],
      ]);

      $momentos = $this->pdo->query("
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

      $sentencia = $this->pdo->prepare("
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

      $this->pdo->commit();
      $_SESSION['mensajes.exito'] = 'Estudiante inscrito exitósamente';
      unset($_SESSION['datos']);
      exit(App::redirect('/inscripciones'));
    } catch (PDOException $error) {
      $this->pdo->rollBack();

      throw $error;
    } catch (InvalidArgumentException $error) {
      $_SESSION['mensajes.error'] = $error->getMessage();
    }

    $_SESSION['datos'] = $inscripcion;
    App::redirect(App::request()->referrer);
  }
}
