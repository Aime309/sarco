<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Representante;

return function (Router $router): void {
  $router->get('/', function (): void {
    $representantes = bd()->query("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, estado_civil as estadoCivil,
      nacionalidad, telefono, correo, fecha_registro as fechaRegistro
      FROM representantes
    ")->fetchAll(PDO::FETCH_CLASS, Representante::class);

    App::render(
      'paginas/representantes/listado',
      compact('representantes'),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Representantes']);
  });

  $router->group('/@cedula:[0-9]{7,8}', function (Router $router): void {
    $router->get('/', function (int $cedula): void {
      $representante = bd()->query("
        SELECT id, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, estado_civil as estadoCivil,
        nacionalidad, telefono, correo, fecha_registro as fechaRegistro,
        genero, correo
        FROM representantes WHERE cedula = $cedula
      ")->fetchObject(Representante::class);
      assert($representante instanceof Representante);

      $estudiantes = bd()->query("
        SELECT id, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
        genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
        id_mama as idMama, id_papa as idPapa FROM estudiantes
        WHERE idMama = '$representante->id' OR idPapa = '$representante->id'
      ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

      $representante->asignarEstudiantes(...$estudiantes);

      App::render('paginas/representantes/perfil', compact('representante'), 'pagina');
      App::render('plantillas/privada', ['titulo' => $representante->nombreCompleto()]);
    });

    $router->get('/editar', function (int $cedula): void {
      $representante = bd()->query("
        SELECT id, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, estado_civil as estadoCivil,
        nacionalidad, telefono, correo, fecha_registro as fechaRegistro
        FROM representantes WHERE cedula = $cedula
      ")->fetchObject(Representante::class);

      App::render('paginas/representantes/editar', compact('representante'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Editar representante']);
    })->addMiddleware(autorizar(Rol::Secretario, Rol::Director));

    $router->post('/', function (int $cedula): void {
      $representante = App::request()->data->getData();

      $sentencia = bd()->prepare("
        UPDATE representantes SET nombres = :nombres, apellidos = :apellidos,
        cedula = :cedula, fecha_nacimiento = :fechaNacimiento,
        estado_civil = :estadoCivil, nacionalidad = :nacionalidad,
        telefono = :telefono, correo = :correo
        WHERE cedula = $cedula
      ");

      $sentencia->bindValue(':nombres', $representante['nombres']);
      $sentencia->bindValue(':apellidos', $representante['apellidos']);
      $sentencia->bindValue(':cedula', $representante['cedula']);
      $sentencia->bindValue(':fechaNacimiento', $representante['fecha_nacimiento']);
      $sentencia->bindValue(':estadoCivil', $representante['estado_civil']);
      $sentencia->bindValue(':nacionalidad', $representante['nacionalidad']);
      $sentencia->bindValue(':telefono', $representante['telefono']);
      $sentencia->bindValue(':correo', $representante['correo']);

      try {
        $sentencia->execute();
        $_SESSION['mensajes.exito'] = 'Representante actualizado exitósamente';
        App::redirect('/representantes');
      } catch (PDOException $error) {
        if (str_contains($error, 'representantes.nombres')) {
          $nombreCompleto = "{$representante['nombres']} {$representante['apellidos']}";
          $_SESSION['mensajes.error'] = "Representante $nombreCompleto ya existe";
        } elseif (str_contains($error, 'representantes.cedula')) {
          $_SESSION['mensajes.error'] = "Representante {$representante['cedula']} ya existe";
        } elseif (str_contains($error, 'representantes.telefono')) {
          $_SESSION['mensajes.error'] = "Teléfono {$representante['telefono']} ya existe";
        } elseif (str_contains($error, 'representantes.correo')) {
          $_SESSION['mensajes.error'] = "Correo {$representante['correo']} ya existe";
        } else {
          throw $error;
        }

        App::redirect("/representantes/$cedula/editar");
      }
    })->addMiddleware(Rol::Secretario, Rol::Director);
  });
};
