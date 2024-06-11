<?php

use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;

function autorizar(Rol ...$roles): callable {
  return static function () use ($roles): void {
    $usuario = App::view()->get('usuario');
    assert($usuario instanceof Usuario);

    foreach ($roles as $rol) {
      if ($usuario->rol() === $rol) {
        return;
      }
    }

    $_SESSION['mensajes.error'] = 'Acceso denegado';

    exit(App::redirect(App::request()->referrer, 403));
  };
}

function permitirSiNoHayDirectoresActivos(): callable {
  return function (): void {
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
  };
}

function mostrarFormularioDeIngresoSiNoEstaAutenticado(): callable {
  return function (): void {
    if (!key_exists('usuario.id', $_SESSION)) {
      App::render('paginas/ingreso', [], 'pagina');

      exit(App::render('plantillas/publica', ['titulo' => 'Ingreso']));
    }

    $sentencia = bd()->prepare('
      SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
      direccion, telefono, correo, rol, esta_activo as estaActivo,
      fecha_registro as fechaRegistro, clave
      FROM usuarios
      WHERE id = ?
    ');

    $sentencia->execute([$_SESSION['usuario.id']]);
    $usuario = $sentencia->fetchObject(Usuario::class);

    App::view()->set('usuario', $usuario);
  };
}

function permitirUsuariosActivos(): callable {
  return function (): void {
    $usuario = App::view()->get('usuario');
    assert($usuario instanceof Usuario);

    if (!$usuario->estaActivo) {
      App::redirect('/salir');

      return;
    }
  };
}

function notificarSiLimiteDePeriodoExcedido(): callable {
  return function (): void {
    $excepciones = [
      '/periodos/nuevo',
      '/periodos/nuevo/'
    ];

    $ultimoPeriodo = bd()->query('
      SELECT anio_inicio + 1 FROM periodos ORDER BY anio_inicio DESC
      LIMIT 1
    ')->fetchColumn();

    $usuarioAutenticado = App::view()->get('usuario');
    assert($usuarioAutenticado instanceof Usuario);

    $fechaActual = date('Y-m-d');
    $fechaLimite = "$ultimoPeriodo-08-01";
    $fechaPreLimite = "$ultimoPeriodo-07-15";

    if ($fechaActual >= $fechaLimite) {
      if ($usuarioAutenticado->esDirector()) {
        $_SESSION['mensajes.advertencia'] = "Período $ultimoPeriodo excedido, debe aperturar un nuevo período escolar";

        if (!in_array(App::request()->url, $excepciones)) {
          App::redirect('/periodos/nuevo');
        }

        return;
      }

      $_SESSION['mensajes.error'] = "Período $ultimoPeriodo excedido, por favor consulte con el director para la apertura de un nuevo período";
      exit(App::redirect('/salir'));
    }

    if ($fechaActual >= $fechaPreLimite) {
      $_SESSION['mensajes.advertencia'] = "Período $ultimoPeriodo excedido, debe aperturar un nuevo período escolar";
    }
  };
}
