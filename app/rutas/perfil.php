<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Modelos\Usuario;

return function (Router $router): void {
  $router->get('/', function (): void {
    App::render('paginas/usuarios/perfil', [], 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Mi perfil']);
  });

  $router->post('/', function (): void {
    $usuario = App::request()->data->getData();

    $sentencia = bd()->prepare("
        UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos,
        cedula = :cedula, fecha_nacimiento = :fechaNacimiento,
        direccion = :direccion, telefono = :telefono, correo = :correo
        WHERE id = :id
      ");

    $sentencia->bindValue(':id', $usuario['id']);
    $sentencia->bindValue(':nombres', $usuario['nombres']);
    $sentencia->bindValue(':apellidos', $usuario['apellidos']);
    $sentencia->bindValue(':cedula', $usuario['cedula'], PDO::PARAM_INT);
    $sentencia->bindValue(':fechaNacimiento', $usuario['fecha_nacimiento']);
    $sentencia->bindValue(':direccion', $usuario['direccion']);
    $sentencia->bindValue(':telefono', $usuario['telefono']);
    $sentencia->bindValue(':correo', $usuario['correo']);

    try {
      $sentencia->execute();
      $_SESSION['mensajes.exito'] = 'Perfil actualizado exitósamente';
    } catch (PDOException $error) {
      if (str_contains($error, 'usuarios.nombres')) {
        $nombreCompleto = "{$usuario['nombres']} {$usuario['apellidos']}";
        $_SESSION['mensajes.error'] = "Usuario $nombreCompleto ya existe";
      } elseif (str_contains($error, 'usuarios.cedula')) {
        $_SESSION['mensajes.error'] = "Usuario {$usuario['cedula']} ya existe";
      } elseif (str_contains($error, 'usuarios.telefono')) {
        $_SESSION['mensajes.error'] = "Teléfono {$usuario['telefono']} ya existe";
      } elseif (str_contains($error, 'usuarios.correo')) {
        $_SESSION['mensajes.error'] = "Correo {$usuario['correo']} ya existe";
      } else {
        throw $error;
      }
    }

    App::redirect('/perfil');
  });

  $router->post('/actualizar-clave', function (): void {
    $claves = App::request()->data->getData();
    $usuario = App::view()->get('usuario');
    $nuevaClave = Usuario::encriptar($claves['nueva_clave']);

    assert($usuario instanceof Usuario);

    if (!$usuario->validarClave($claves['antigua_clave'])) {
      $_SESSION['mensajes.error'] = 'Antigua contraseña incorrecta';
    } elseif ($claves['antigua_clave'] === $claves['nueva_clave']) {
      $_SESSION['mensajes.error'] = 'La nueva contraseña no puede ser igual a la anterior';
    } elseif ($claves['nueva_clave'] !== $claves['confirmar_clave']) {
      $_SESSION['mensajes.error'] = 'La nueva contraseña y su confirmación no coinciden';
    }

    if (key_exists('mensajes.error', $_SESSION)) {
      App::redirect('/perfil');

      return;
    }

    $sentencia = bd()
      ->prepare('UPDATE usuarios SET clave = :clave WHERE cedula = :cedula');

    $sentencia->bindValue(':clave', $nuevaClave);
    $sentencia->bindValue(':cedula', $usuario->cedula, PDO::PARAM_INT);
    $sentencia->execute();

    $_SESSION['mensajes.exito'] = 'Contraseña actualizada exitósamente';
    App::redirect('/perfil');
  });

  $router->post('/desactivar', function (): void {
    $usuario = App::view()->get('usuario');

    assert($usuario instanceof Usuario);

    bd()->query("
      UPDATE usuarios
      SET esta_activo = FALSE
      WHERE id = '$usuario->id'
    ");

    $_SESSION['mensajes.exito'] = 'Cuenta desactivada existósamente';
    App::redirect('/salir');
  });
};
