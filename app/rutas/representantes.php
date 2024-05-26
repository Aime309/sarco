<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
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
    $router->get('/editar', function (int $cedula): void {
      $representante = bd()->query("
        SELECT id, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, estado_civil as estadoCivil,
        nacionalidad, telefono, correo, fecha_registro as fechaRegistro
        FROM representantes WHERE cedula = $cedula
      ")->fetchObject(Representante::class);

      App::render('paginas/representantes/editar', compact('representante'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Editar representante']);
    });

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
    });
  }, [autorizar(Rol::Secretario)]);
};
