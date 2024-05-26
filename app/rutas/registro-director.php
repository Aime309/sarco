<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;
use Symfony\Component\Uid\UuidV4;

return function (Router $router): void {
  $router->get('/', function (): void {
    App::render('paginas/registro', [], 'pagina');
    App::render('plantillas/publica', ['titulo' => 'Regístrate']);
  });

  $router->post('/', function (): void {
    $usuario = App::request()->data->getData();

    try {
      Usuario::asegurarValidez($usuario);

      $clave = Usuario::encriptar($usuario['clave']);
      $id = new UuidV4;

      $sentencia = bd()->prepare("
        INSERT INTO usuarios (
          id, nombres, apellidos, cedula, fecha_nacimiento, genero, telefono,
          correo, direccion, clave, rol
        ) VALUES (
          :id, :nombres, :apellidos, :cedula, :fechaNacimiento, :genero,
          :telefono, :correo, :direccion, :clave, :rol
        )
      ");

      $sentencia->execute([
        ':id' => $id,
        ':nombres' => mb_convert_case($usuario['nombres'], MB_CASE_TITLE),
        ':apellidos' => mb_convert_case($usuario['apellidos'], MB_CASE_TITLE),
        ':cedula' => $usuario['cedula'],
        ':fechaNacimiento' => $usuario['fecha_nacimiento'],
        ':genero' => $usuario['genero'],
        ':telefono' => $usuario['telefono'],
        ':correo' => $usuario['correo'],
        ':direccion' => mb_convert_case($usuario['direccion'], MB_CASE_TITLE),
        ':clave' => $clave,
        ':rol' => Rol::Director->value
      ]);

      $_SESSION['mensajes.exito'] = 'Director registrado existósamente';
      $_SESSION['usuario.id'] = $id;
      unset($_SESSION['datos']);

      exit(App::redirect('/'));
    } catch (PDOException $error) {
      if (str_contains($error->getMessage(), 'usuarios.correo')) {
        $_SESSION['mensajes.error'] = "Ya existe un usuario con el correo {$usuario['correo']}";
      } elseif (str_contains($error->getMessage(), 'usuarios.nombres')) {
        $_SESSION['mensajes.error'] = "Ya existe el usuario {$usuario['nombres']} {$usuario['apellidos']}";
      } elseif (str_contains($error->getMessage(), 'usuarios.cedula')) {
        $_SESSION['mensajes.error'] = "Ya existe un usuario con la cédula {$usuario['cedula']}";
      } elseif (str_contains($error->getMessage(), 'usuarios.telefono')) {
        $_SESSION['mensajes.error'] = "El teléfono {$usuario['telefono']} ya ha sido ocupado";
      } else {
        throw $error;
      }
    } catch (InvalidArgumentException $error) {
      $_SESSION['mensajes.error'] = $error->getMessage();
    }

    $_SESSION['datos'] = $usuario;
    App::redirect(App::request()->referrer);
  });
};
