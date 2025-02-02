<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Maestro;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Sala;
use Symfony\Component\Uid\UuidV4;

return function (Router $router): void {
  $router->get('/', function (): void {
    $periodos = bd()->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC
    ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

    foreach ($periodos as $periodo) {
      $momentos = bd()->query("
        SELECT m.id, numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio,
        mes_cierre as mesCierre,
        dia_cierre as diaCierre,
        m.fecha_registro as fechaRegistro
        FROM momentos m
        JOIN periodos p
        ON id_periodo = p.id
        WHERE id_periodo = '{$periodo->id}'
        ORDER BY numero
      ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

      bd()->beginTransaction();

      try {
        bd()->exec("DELETE FROM momentos WHERE id_periodo = '$periodo->id'");
        bd()->exec("DELETE FROM periodos WHERE id = '$periodo->id'");
        $periodo->sePuedeEliminar = true;
      } catch (PDOException) {
        $periodo->sePuedeEliminar = false;
      }

      bd()->rollBack();
      $periodo->asignarMomentos(...$momentos);
    }

    App::render('paginas/periodos/listado', compact('periodos'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Períodos']);
  });

  $router->post('/', function (): void {
    $periodo = App::request()->data->getData();
    $añoInicio = (int) $periodo['anio_inicio'];
    $momentos = $periodo['momentos'];

    bd()->beginTransaction();

    try {
      $momentos = array_map(function (array $momento): array {
        [$fechaInicio, $mesInicio, $diaInicio] = explode('-', $momento['inicio']);
        [$fechaCierre, $mesCierre, $diaCierre] = explode('-', $momento['fin']);

        return [
          'inicio' => [
            'año' => $fechaInicio,
            'mes' => $mesInicio,
            'dia' => $diaInicio
          ],
          'cierre' => [
            'año' => $fechaCierre,
            'mes' => $mesCierre,
            'dia' => $diaCierre
          ],
          'inicioCompleto' => $momento['inicio'],
          'cierreCompleto' => $momento['fin'],
          'id' => new UuidV4
        ];
      }, $momentos);

      if (
        $momentos[1]['inicio']['año'] < $añoInicio
        || $momentos[1]['cierre']['año'] < $añoInicio
        || $momentos[2]['inicio']['año'] < $añoInicio
        || $momentos[2]['cierre']['año'] < $añoInicio
        || $momentos[3]['inicio']['año'] < $añoInicio
        || $momentos[3]['cierre']['año'] < $añoInicio
        || $momentos[1]['inicio']['año'] > ($añoInicio + 1)
        || $momentos[1]['cierre']['año'] > ($añoInicio + 1)
        || $momentos[2]['inicio']['año'] > ($añoInicio + 1)
        || $momentos[2]['cierre']['año'] > ($añoInicio + 1)
        || $momentos[3]['inicio']['año'] > ($añoInicio + 1)
        || $momentos[3]['cierre']['año'] > ($añoInicio + 1)
      ) {
        throw new Error("Los momentos deben del año $añoInicio");
      } elseif (
        $momentos[1]['inicioCompleto'] >= $momentos[1]['cierreCompleto']
      ) {
        throw new Error('El inicio del 1er Momento debe ser antes del fin');
      } elseif (
        $momentos[1]['cierreCompleto'] >= $momentos[2]['inicioCompleto']
      ) {
        throw new Error('El fin del 1er Momento debe ser antes del inicio del 2do Momento');
      } elseif (
        $momentos[2]['inicioCompleto'] >= $momentos[2]['cierreCompleto']
      ) {
        throw new Error('El inicio del 2do Momento debe ser antes del fin');
      } elseif (
        $momentos[2]['cierreCompleto'] >= $momentos[3]['inicioCompleto']
      ) {
        throw new Error('El fin del 2do Momento debe ser antes del inicio del 3er Momento');
      } elseif (
        $momentos[3]['inicioCompleto'] >= $momentos[3]['cierreCompleto']
      ) {
        throw new Error('El inicio del 3er Momento debe ser antes del fin');
      }

      $idPeriodo = new UuidV4;

      bd()->query("
          INSERT INTO periodos (id, anio_inicio)
          VALUES ('$idPeriodo', $añoInicio)
        ");

      $sentencia = bd()->prepare("
          INSERT INTO momentos (id, numero, mes_inicio, dia_inicio, mes_cierre,
          dia_cierre, id_periodo) VALUES (:id, :numero, :mesInicio, :diaInicio,
          :mesCierre, :diaCierre, '$idPeriodo')
        ");

      foreach ($momentos as $numero => $momento) {
        $sentencia->bindValue(':id', $momento['id']);
        $sentencia->bindValue(':numero', $numero, PDO::PARAM_INT);
        $sentencia->bindValue(':mesInicio', $momento['inicio']['mes'], PDO::PARAM_INT);
        $sentencia->bindValue(':diaInicio', $momento['inicio']['dia'], PDO::PARAM_INT);
        $sentencia->bindValue(':mesCierre', $momento['cierre']['mes'], PDO::PARAM_INT);
        $sentencia->bindValue(':diaCierre', $momento['cierre']['dia'], PDO::PARAM_INT);

        $sentencia->execute();
      }

      bd()->commit();
      $_SESSION['mensajes.exito'] = "Período $añoInicio aperturado exitósamente";
      unset($_SESSION['datos']);
      App::redirect('/periodos');

      return;
    } catch (PDOException $error) {
      if (str_contains($error, 'periodos.anio_inicio')) {
        $_SESSION['mensajes.error'] = "Periodo $añoInicio ya fue aperturado";
      } else {
        throw $error;
      }
    } catch (Error $error) {
      $_SESSION['mensajes.error'] = $error->getMessage();
    }

    bd()->rollBack();
    $_SESSION['datos'] = $periodo;
    App::redirect(App::request()->referrer);
  });

  $router->get('/nuevo', function (): void {
    $ultimoPeriodo = bd()->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos
      ORDER BY inicio DESC LIMIT 1
    ")->fetchObject(Periodo::class) ?: null;

    App::render('paginas/periodos/nuevo', compact('ultimoPeriodo'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Nuevo período']);
  });

  $router->group('/@periodo:[0-9]{4}', function (Router $router): void {
    $router->get('/', function (int $periodo): void {
      $periodo = bd()->query("
        SELECT p.id, p.anio_inicio as inicio, p.fecha_registro as fechaRegistro
        FROM periodos p
        WHERE inicio = $periodo
      ")->fetchObject(Periodo::class);

      $momentos = bd()->query("
        SELECT m.id, numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio,
        mes_cierre as mesCierre,
        dia_cierre as diaCierre,
        m.fecha_registro as fechaRegistro
        FROM momentos m
        JOIN periodos p
        ON id_periodo = p.id
        WHERE id_periodo = '{$periodo->id}'
        ORDER BY numero
      ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

      $periodo->asignarMomentos(...$momentos);

      $asignaciones = bd()->query("
        SELECT * FROM asignaciones_de_salas WHERE id_periodo = '$periodo->id'
      ")->fetchAll(PDO::FETCH_ASSOC);

      $salas = bd()->query("
        SELECT id, fecha_registro as fechaRegistro, nombre,
        edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva FROM salas
      ")->fetchAll(PDO::FETCH_CLASS, Sala::class);

      foreach ($asignaciones as $asignacion) {
        $sala = bd()->query("
          SELECT id, fecha_registro as fechaRegistro, nombre,
          edad_minima as edadMinima, edad_maxima as edadMaxima,
          esta_activa as estaActiva FROM salas WHERE id = '{$asignacion['id_sala']}'
        ")->fetchObject(Sala::class);
        assert($sala instanceof Sala);

        $aula = bd()->query("
          SELECT id, fecha_registro as fechaRegistro, codigo, tipo FROM aulas
          WHERE id = '{$asignacion['id_aula']}'
        ")->fetchObject(Aula::class);

        $aula = bd()->query("
          SELECT id, fecha_registro as fechaRegistro, codigo, tipo FROM aulas
          WHERE id = '{$asignacion['id_aula']}'
        ")->fetchObject(Aula::class);

        $docente1 = bd()->query("
          SELECT id, fecha_registro as fechaRegistro, nombres, apellidos,
          cedula, fecha_nacimiento as fechaNacimiento, genero, telefono,
          correo, direccion, clave, esta_activo as estaActivo, rol
          FROM usuarios WHERE id = '{$asignacion['id_docente1']}'
        ")->fetchObject(Maestro::class);

        $docente2 = bd()->query("
          SELECT id, fecha_registro as fechaRegistro, nombres, apellidos,
          cedula, fecha_nacimiento as fechaNacimiento, genero, telefono,
          correo, direccion, clave, esta_activo as estaActivo, rol
          FROM usuarios WHERE id = '{$asignacion['id_docente2']}'
        ")->fetchObject(Maestro::class);

        $docente3 = null;
        $docentes = [$docente1, $docente2];

        if ($asignacion['id_docente3']) {
          $docente3 = bd()->query("
            SELECT id, fecha_registro as fechaRegistro, nombres, apellidos,
            cedula, fecha_nacimiento as fechaNacimiento, genero, telefono,
            correo, direccion, clave, esta_activo as estaActivo, rol
            FROM usuarios WHERE id = '{$asignacion['id_docente3']}'
          ")->fetchObject(Maestro::class);

          $docentes[] = $docente3;
        }

        $nuevosEstudiantes = bd()->query("
          SELECT e.id, e.fecha_registro as fechaRegistro, nombres, apellidos,
          cedula, fecha_nacimiento as fechaNacimiento, genero,
          lugar_nacimiento as lugarNacimiento, tipo_sangre as grupoSanguineo,
          id_mama as idMama, id_papa as idPapa
          FROM estudiantes e
          JOIN inscripciones i
          ON i.id_estudiante = e.id
          WHERE i.id_asignacion_sala = '{$asignacion['id']}'
        ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

        $sala->aula = $aula;
        $sala->asignarDocentes(...$docentes);
        $salasAsignadas[] = $sala;
      }

      $cantidadDeInscripciones = bd()->query("
        SELECT COUNT(id) FROM inscripciones WHERE id_periodo = '$periodo->id'
      ")->fetchColumn();

      $detalles['salas'] = $salas;
      $detalles['salasAsignadas'] = $salasAsignadas ?? [];
      $detalles['nuevosEstudiantes'] = $nuevosEstudiantes ?? [];

      App::render(
        'paginas/periodos/detalles',
        compact('periodo', 'detalles', 'cantidadDeInscripciones'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => "Periodo $periodo"]);
    });

    $router->get('/editar', function (int $periodo): void {
      $periodo = bd()->query("
        SELECT p.id, p.anio_inicio as inicio, p.fecha_registro as fechaRegistro
        FROM periodos p
        WHERE inicio = $periodo
      ")->fetchObject(Periodo::class);

      $momentos = bd()->query("
        SELECT m.id, numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio,
        mes_cierre as mesCierre,
        dia_cierre as diaCierre,
        m.fecha_registro as fechaRegistro
        FROM momentos m
        JOIN periodos p
        ON id_periodo = p.id
        WHERE id_periodo = '{$periodo->id}'
        ORDER BY numero
      ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

      $periodo->asignarMomentos(...$momentos);

      App::render('paginas/periodos/editar', compact('periodo'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Editar período']);
    });

    $router->post('/', function (int $viejoAñoInicio): void {
      $periodo = App::request()->data->getData();
      $idPeriodo = $periodo['id_periodo'];
      $añoInicio = (int) $periodo['anio_inicio'];
      $momentos = $periodo['momentos'];

      bd()->beginTransaction();

      try {
        $momentos = array_map(function (array $momento): array {
          [$fechaInicio, $mesInicio, $diaInicio] = explode('-', $momento['inicio']);
          [$fechaCierre, $mesCierre, $diaCierre] = explode('-', $momento['fin']);

          return [
            'inicio' => [
              'año' => $fechaInicio,
              'mes' => $mesInicio,
              'dia' => $diaInicio
            ],
            'cierre' => [
              'año' => $fechaCierre,
              'mes' => $mesCierre,
              'dia' => $diaCierre
            ],
            'inicioCompleto' => $momento['inicio'],
            'cierreCompleto' => $momento['fin'],
          ];
        }, $momentos);

        if (
          $momentos[1]['inicio']['año'] < $añoInicio
          || $momentos[1]['cierre']['año'] < $añoInicio
          || $momentos[2]['inicio']['año'] < $añoInicio
          || $momentos[2]['cierre']['año'] < $añoInicio
          || $momentos[3]['inicio']['año'] < $añoInicio
          || $momentos[3]['cierre']['año'] < $añoInicio
          || $momentos[1]['inicio']['año'] > ($añoInicio + 1)
          || $momentos[1]['cierre']['año'] > ($añoInicio + 1)
          || $momentos[2]['inicio']['año'] > ($añoInicio + 1)
          || $momentos[2]['cierre']['año'] > ($añoInicio + 1)
          || $momentos[3]['inicio']['año'] > ($añoInicio + 1)
          || $momentos[3]['cierre']['año'] > ($añoInicio + 1)
        ) {
          throw new Error("Los momentos deben del año $añoInicio");
        } elseif (
          $momentos[1]['inicioCompleto'] >= $momentos[1]['cierreCompleto']
        ) {
          throw new Error('El inicio del 1er Momento debe ser antes del fin');
        } elseif (
          $momentos[1]['cierreCompleto'] >= $momentos[2]['inicioCompleto']
        ) {
          throw new Error('El fin del 1er Momento debe ser antes del inicio del 2do Momento');
        } elseif (
          $momentos[2]['inicioCompleto'] >= $momentos[2]['cierreCompleto']
        ) {
          throw new Error('El inicio del 2do Momento debe ser antes del fin');
        } elseif (
          $momentos[2]['cierreCompleto'] >= $momentos[3]['inicioCompleto']
        ) {
          throw new Error('El fin del 2do Momento debe ser antes del inicio del 3er Momento');
        } elseif (
          $momentos[3]['inicioCompleto'] >= $momentos[3]['cierreCompleto']
        ) {
          throw new Error('El inicio del 3er Momento debe ser antes del fin');
        }

        $sentencia = bd()->prepare("
            UPDATE periodos SET anio_inicio = :inicio
            WHERE id = :id
          ");

        $sentencia->execute([':inicio' => $añoInicio, ':id' => $idPeriodo]);

        $sentencia = bd()->prepare("
            UPDATE momentos SET mes_inicio = :mesInicio, dia_inicio = :diaInicio,
            mes_cierre = :mesCierre, dia_cierre = :diaCierre
            WHERE numero = :numero AND id_periodo = :idPeriodo
          ");

        foreach ($momentos as $numero => $momento) {
          $sentencia->execute([
            ':mesInicio' => $momento['inicio']['mes'],
            ':diaInicio' => $momento['inicio']['dia'],
            ':mesCierre' => $momento['cierre']['mes'],
            ':diaCierre' => $momento['cierre']['dia'],
            ':numero' => $numero,
            ':idPeriodo' => $idPeriodo
          ]);
        }

        bd()->commit();
        $_SESSION['mensajes.exito'] = "Período $viejoAñoInicio actualizado a $añoInicio exitósamente";
        unset($_SESSION['datos']);
        App::redirect('/periodos');

        return;
      } catch (PDOException $error) {
        if (str_contains($error, 'periodos.anio_inicio')) {
          $_SESSION['mensajes.error'] = "Periodo $añoInicio ya fue aperturado";
        } else {
          throw $error;
        }
      } catch (Error $error) {
        $_SESSION['mensajes.error'] = $error->getMessage();
      }

      bd()->rollBack();
      $_SESSION['datos'] = $periodo;
      App::redirect(App::request()->referrer);
    });

    $router->get('/eliminar', function (int $añoInicio): void {
      $periodo = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos WHERE anio_inicio = $añoInicio
      ")->fetchObject(Periodo::class);

      bd()->beginTransaction();

      try {
        bd()->exec("DELETE FROM momentos WHERE id_periodo = '$periodo->id'");
        bd()->exec("DELETE FROM periodos WHERE id = '$periodo->id'");

        $_SESSION['mensajes.exito'] = 'Período eliminado exitósamente';
        bd()->commit();
      } catch (PDOException) {
        $_SESSION['mensajes.error'] = 'No se puede eliminar el período porque ya tiene inscripciones o asignaciones registradas';
        bd()->rollBack();
      }

      App::redirect(App::request()->referrer);
    });
  });
};
