<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Maestro;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Usuario;
use Symfony\Component\Uid\UuidV4;

return function (Router $router): void {
  $router->get('/', function (): void {
    $salas = bd()->query("
      SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
      esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
    ")->fetchAll(PDO::FETCH_CLASS, Sala::class);

    bd()->beginTransaction();

    foreach ($salas as $sala) {
      try {
        bd()->exec("DELETE FROM salas WHERE id = '{$sala->id}'");
        $sala->sePuedeEliminar = true;
      } catch (PDOException) {
        $sala->sePuedeEliminar = false;
      }
    }

    bd()->rollBack();

    App::render('paginas/salas/listado', compact('salas'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Salas']);
  });

  $router->post('/', function (): void {
    $sala = App::request()->data->getData();

    $sentencia = bd()->prepare('
      INSERT INTO salas (id, nombre, edad_minima, edad_maxima)
      VALUES (:id, :nombre, :edadMinima, :edadMaxima)
    ');

    $sentencia->bindValue(
      ':nombre',
      mb_convert_case($sala['nombre'], MB_CASE_TITLE)
    );

    $sentencia->bindValue(':id', new UuidV4);
    $sentencia->bindValue(':edadMinima', $sala['edad_minima'], PDO::PARAM_INT);
    $sentencia->bindValue(':edadMaxima', $sala['edad_maxima'], PDO::PARAM_INT);

    try {
      $sentencia->execute();
      $_SESSION['mensajes.exito'] = "Sala {$sala['nombre']} aperturada exitósamente";
      App::redirect('/salas');
    } catch (PDOException $error) {
      if (str_contains($error, 'salas.nombre')) {
        $_SESSION['mensajes.error'] = "Sala {$sala['nombre']} ya existe";
      } else {
        throw $error;
      }

      App::redirect('/salas/nueva');
    }
  });

  $router->get('/nueva', function (): void {
    App::render('paginas/salas/nueva', [], 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Aperturar sala']);
  });

  $router->get('/asignar', function (): void {
    $periodos = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos ORDER BY inicio DESC
      ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

    $periodoActual = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos ORDER BY inicio DESC LIMIT 1
      ")->fetchObject(Periodo::class) ?: null;

    $idAutenticado = App::view()->get('usuario')->id;

    $maestros = bd()->query("
        SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
        direccion, telefono, correo, rol, esta_activo as estaActivo,
        fecha_registro as fechaRegistro
        FROM usuarios WHERE rol = 'Docente' AND id != '$idAutenticado'
      ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

    $salas = bd()->query("
        SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
      ")->fetchAll(PDO::FETCH_CLASS, Sala::class);

    $aulas = bd()->query("
        SELECT id, codigo, fecha_registro as fechaRegistro, tipo FROM aulas
      ")->fetchAll(PDO::FETCH_CLASS, Aula::class);

    $asignaciones = bd()->query("
        SELECT id as idAsignacion, id_sala as idSala,
        id_aula as idAula, id_periodo as idPeriodo, id_docente1 as idDocente1,
        id_docente2 as idDocente2, id_docente3 as idDocente3
        FROM asignaciones_de_salas
      ")->fetchAll(PDO::FETCH_ASSOC);

    App::render(
      'paginas/salas/asignar',
      compact('periodos', 'periodoActual', 'maestros', 'salas', 'aulas', 'asignaciones'),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Asignar maestros a sala']);
  });

  $router->post('/asignar', function (): void {
    $asignacion = App::request()->data->getData();

    $sentencia = bd()->prepare("
      INSERT INTO asignaciones_de_salas (id, id_sala, id_aula, id_periodo,
      id_docente1, id_docente2, id_docente3) VALUES (:id, :idSala, :idAula,
      :idPeriodo, :idDocente1, :idDocente2, :idDocente3)
    ");

    $sentencia->execute([
      ':id' => new UuidV4,
      ':idSala' => $asignacion['id_sala'],
      ':idAula' => $asignacion['id_aula'],
      ':idPeriodo' => $asignacion['id_periodo'],
      ':idDocente1' => $asignacion['id_maestro'][1],
      ':idDocente2' => $asignacion['id_maestro'][2],
      ':idDocente3' => $asignacion['id_maestro'][3] ?? null,
    ]);

    $_SESSION['mensajes.exito'] = 'Maestros asignados exitósamente';
    App::redirect('/');
  });

  $router->group('/@id', function (Router $router): void {
    $router->get('/', function (string $id): void {
      $consulta = '
        SELECT id, fecha_registro as fechaRegistro, nombre,
        edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva FROM salas WHERE id = ?
      ';

      $sentencia = bd()->prepare($consulta);
      $sentencia->execute([$id]);
      $sala = $sentencia->fetchObject(Sala::class);
      assert($sala instanceof Sala);

      $asignaciones = bd()->query("
        SELECT a.id, id_aula, p.anio_inicio as periodo,
        id_docente1, id_docente2, id_docente3
        FROM asignaciones_de_salas a
        JOIN periodos p
        ON a.id_periodo = p.id
        WHERE id_sala = '$sala->id'
        ORDER BY periodo DESC
      ")->fetchAll(PDO::FETCH_ASSOC);

      $detalles = [];

      foreach ($asignaciones as $asignacion) {
        $periodo = $asignacion['periodo'] . '-' . ($asignacion['periodo'] + 1);

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

        if ($asignacion['id_docente3']) {
          $docente3 = bd()->query("
            SELECT id, fecha_registro as fechaRegistro, nombres, apellidos,
            cedula, fecha_nacimiento as fechaNacimiento, genero, telefono,
            correo, direccion, clave, esta_activo as estaActivo, rol
            FROM usuarios WHERE id = '{$asignacion['id_docente3']}'
          ")->fetchObject(Maestro::class);
        }

        $detalles[$periodo] = compact('aula', 'docente1', 'docente2', 'docente3');
      }

      dd($detalles);

      App::render('paginas/salas/detalles', [], 'pagina');
      App::render('plantillas/privada', ['titulo' => '<nombre de sala>']);
    });

    $router->get('/editar', function (string $id): void {
      $sala = bd()->query("
        SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
        WHERE id = '$id'
      ")->fetchObject(Sala::class);

      App::render('paginas/salas/editar', compact('sala'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Editar sala']);
    });

    $router->post('/', function (string $id): void {
      $sala = App::request()->data->getData();

      $sentencia = bd()->prepare("
          UPDATE salas SET nombre = :nombre, edad_minima = :edadMinima,
          edad_maxima = :edadMaxima WHERE id = '$id'
        ");

      $sentencia->bindValue(':nombre', $sala['nombre']);
      $sentencia->bindValue(':edadMinima', $sala['edad_minima'], PDO::PARAM_INT);
      $sentencia->bindValue(':edadMaxima', $sala['edad_maxima'], PDO::PARAM_INT);

      try {
        $sentencia->execute();
        $_SESSION['mensajes.exito'] = 'Sala actualizada exitósamente';
        App::redirect('/salas');
      } catch (PDOException $error) {
        if (str_contains($error, 'salas.nombre')) {
          $_SESSION['mensajes.error'] = "Sala {$sala['nombre']} ya existe";
        } else {
          throw $error;
        }

        App::redirect("/salas/$id");
      }
    });

    $router->get('/habilitar', function (string $id): void {
      bd()->query("UPDATE salas SET esta_activa = TRUE WHERE id = '$id'");
      $_SESSION['mensajes.exito'] = 'Sala habilitada exitósamente';
      App::redirect('/salas');
    });

    $router->get('/inhabilitar', function (string $id): void {
      bd()->query("UPDATE salas SET esta_activa = FALSE WHERE id = '$id'");
      $_SESSION['mensajes.exito'] = 'Sala inhabilitada exitósamente';
      App::redirect('/salas');
    });

    $router->get('/eliminar', function (string $id): void {
      $sentencia = bd()->prepare("DELETE FROM salas WHERE id = ?");

      try {
        $sentencia->execute([$id]);
        $_SESSION['mensajes.exito'] = 'Sala eliminada exitósamente';
      } catch (PDOException) {
        $_SESSION['mensajes.error'] = 'No se puede eliminar una sala que haya sido asignada';
      }

      App::redirect(App::request()->referrer);
    });
  });
};
