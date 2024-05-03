<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;

App::route('GET /salir', function (): void {
  unset($_SESSION['usuario.id']);
  unset($_SESSION['usuario.recordar']);
  App::redirect('/');
});

App::group('/registrate', function (Router $router): void {
  $router->get('/', function (): void {
    App::render('paginas/registro', [], 'pagina');
    App::render('plantillas/publica', ['titulo' => 'Regístrate']);
  });

  $router->post('/', function (): void {
    $usuario = App::request()->data->getData();
    $rol = Rol::Director->obtenerPorGenero(Genero::from($usuario['genero']));
    $clave = password_hash($usuario['clave'], PASSWORD_DEFAULT);

    $sentencia = bd()->prepare("
      INSERT INTO usuarios (
        nombres, apellidos, cedula, fecha_nacimiento, direccion, telefono,
        correo, clave, rol
      ) VALUES (
        :nombres, :apellidos, :cedula, :fechaNacimiento, :direccion,
        :telefono, :correo, :clave, :rol
      )
    ");

    $sentencia->bindValue(':nombres', $usuario['nombres']);
    $sentencia->bindValue(':apellidos', $usuario['apellidos']);
    $sentencia->bindValue(':cedula', $usuario['cedula'], PDO::PARAM_INT);
    $sentencia->bindValue(':fechaNacimiento', $usuario['fecha_nacimiento']);
    $sentencia->bindValue(':direccion', $usuario['direccion']);
    $sentencia->bindValue(':telefono', $usuario['telefono']);
    $sentencia->bindValue(':correo', $usuario['correo']);
    $sentencia->bindValue(':clave', $clave);
    $sentencia->bindValue(':rol', $rol);

    $sentencia->execute();
    $_SESSION['mensajes.exito'] = "$rol registrado existósamente";
    $_SESSION['usuario.id'] = bd()->lastInsertId();
    App::redirect('/');
  });
}, [function (): void {
  $hayDirectoresActivos = bd()
    ->query("
      SELECT COUNT(id)
      FROM usuarios
      WHERE rol IN ('Director', 'Directora')
      AND esta_activo = true
    ")->fetchColumn();

  if ($hayDirectoresActivos) {
    $_SESSION['mensajes.error'] = 'Ya existe un director activo';

    exit(App::redirect('/'));
  }
}]);

App::post('/ingresar', function (): void {
  $credenciales = App::request()->data->getData();

  $sentencia = bd()->prepare(<<<sql
    SElECT id, clave FROM usuarios WHERE cedula = ?
  sql);

  $sentencia->execute([$credenciales['cedula']]);
  $usuarioEncontrado = $sentencia->fetch() ?: ['clave' => ''];

  if (!password_verify($credenciales['clave'], $usuarioEncontrado['clave'])) {
    $_SESSION['mensajes.error'] = 'Cédula o contraseña incorrecta';
    App::redirect('/');

    exit;
  }

  if (key_exists('recordar', $credenciales)) {
    $_SESSION['usuario.recordar'] = true;
  }

  $_SESSION['usuario.id'] = $usuarioEncontrado['id'];
  App::redirect('/');
});

App::group('/', function (Router $router): void {
  $router->get('/', function (): void {
    $cantidadDeUsuarios = (int) bd()
      ->query('SELECT COUNT(id) FROM usuarios')
      ->fetchColumn();

    $cantidadDeRepresentantes = (int) bd()
      ->query('SELECT COUNT(id) FROM representantes')
      ->fetchColumn();

    App::render(
      'paginas/inicio',
      compact('cantidadDeUsuarios', 'cantidadDeRepresentantes'),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Inicio']);
  });

  $router->group('usuarios', function (Router $router): void {
    $router->get('/', function (): void {
      $idAutenticado = App::view()->get('usuario')->id;

      $usuarios = bd()
        ->query("
          SELECT id, nombres, apellidos, cedula,
          fecha_nacimiento as fechaNacimiento, direccion, telefono, correo,
          rol, esta_activo as estaActivo, fecha_registro as fechaRegistro
          FROM usuarios
          WHERE id != $idAutenticado
        ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

      App::render('paginas/usuarios/listado', compact('usuarios'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Usuarios']);
    });

    $router->post('/', function (): void {

      $usuario = App::request()->data->getData();
      $genero = Genero::from($usuario['genero']);
      $rol = Rol::obtenerPorNombre($usuario['rol'])->obtenerPorGenero($genero);
      $clave = password_hash($usuario['clave'], PASSWORD_DEFAULT);

      $sentencia = bd()->prepare("
        INSERT INTO usuarios (
          nombres, apellidos, cedula, fecha_nacimiento, direccion, telefono,
          correo, clave, rol
        ) VALUES (
          :nombres, :apellidos, :cedula, :fechaNacimiento, :direccion,
          :telefono, :correo, :clave, :rol
        )
      ");

      $sentencia->bindValue(':nombres', $usuario['nombres']);
      $sentencia->bindValue(':apellidos', $usuario['apellidos']);
      $sentencia->bindValue(':cedula', $usuario['cedula'], PDO::PARAM_INT);
      $sentencia->bindValue(':fechaNacimiento', $usuario['fecha_nacimiento']);
      $sentencia->bindValue(':direccion', $usuario['direccion']);
      $sentencia->bindValue(':telefono', $usuario['telefono']);
      $sentencia->bindValue(':correo', $usuario['correo']);
      $sentencia->bindValue(':clave', $clave);
      $sentencia->bindValue(':rol', $rol);

      try {
        $sentencia->execute();
        $mensaje = $genero === Genero::Femenino ? 'registrada' : 'registrado';
        $_SESSION['mensajes.exito'] = "$rol $mensaje exitósamente";
      } catch (PDOException $error) {
        if (str_contains($error, 'usuarios.nombres')) {
          $_SESSION['mensajes.error'] = "Usuario {$usuario['nombres']} {$usuario['apellidos']} ya existe";
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

      App::redirect('/usuarios');
    });

    $router->get('/nuevo', function (): void {
      App::render('paginas/usuarios/nuevo', [], 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Nuevo usuario']);
    });

    $router->group('/@cedula:[0-9]{7,8}', function (Router $router): void {
      $router->get('/activar', function (int $cedula): void {
        bd()->query("UPDATE usuarios SET esta_activo = TRUE WHERE cedula = $cedula");
        $_SESSION['mensajes.exito'] = 'Usuario activado existósamente';
        App::redirect('/usuarios');
      });

      $router->get('/desactivar', function (int $cedula): void {
        bd()->query("UPDATE usuarios SET esta_activo = FALSE WHERE cedula = $cedula");
        $_SESSION['mensajes.exito'] = 'Usuario desactivado existósamente';
        App::redirect('/usuarios');
      });
    });
  });
}, [function (): void {
  if (!key_exists('usuario.id', $_SESSION)) {
    App::render('paginas/ingreso', [], 'pagina');

    exit(App::render('plantillas/publica', ['titulo' => 'Ingreso']));
  }

  $sentencia = bd()->prepare('
    SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
    direccion, telefono, correo, rol, esta_activo as estaActivo,
    fecha_registro as fechaRegistro
    FROM usuarios
    WHERE id = ?
  ');

  $sentencia->execute([$_SESSION['usuario.id']]);
  $usuario = $sentencia->fetchObject(Usuario::class);

  App::view()->set('usuario', $usuario);
}]);
