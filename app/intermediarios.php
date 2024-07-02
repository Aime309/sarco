<?php

use Illuminate\Container\Container;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Usuario;
use SARCO\Repositorios\RepositorioDeBoletines;
use SARCO\Repositorios\RepositorioDeUsuarios;

function autorizar(Rol ...$roles): callable {
  return static function () use ($roles): void {
    $usuario = App::view()->get('usuario');
    assert($usuario instanceof Usuario);

    foreach ($roles as $rol) {
      if ($usuario->rol() === $rol) {
        return;
      }
    }

    if (str_contains(App::request()->url, '/api')) {
      header('Content-Type: application/json');
      App::halt(403, json_encode(['error' => 'Acceso denegado']));

      exit;
    }

    $_SESSION['mensajes.error'] = 'Acceso denegado';

    exit(App::redirect(App::request()->referrer ?: '/', 403));
  };
}

function permitirSiNoHayDirectoresActivos(): callable {
  $director = Rol::Director;

  return function () use ($director): void {
    $hayDirectoresActivos = bd()
      ->query("
      SELECT COUNT(id)
      FROM usuarios
      WHERE rol = '{$director->value}'
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

function permitirUsuariosAutenticados(): callable {
  return function (): void {
    if (!key_exists('usuario.id', $_SESSION)) {
      header('Content-Type: application/json');

      App::halt(401, json_encode([
        'error' => 'Acceso denegado, debes estar autenticado'
      ]));
    }

    $usuario = App::get('contenedor')
      ?->get(RepositorioDeUsuarios::class)
      ->buscarPorId($_SESSION['usuario.id']);

    App::view()->set('usuario', $usuario);
  };
}

function permitirUsuariosActivos(): callable {
  return function (): void {
    $usuario = App::view()->get('usuario') ?: null;

    if (!$usuario?->estaActivo) {
      $_SESSION['mensajes.error'] = 'Has sido desactivado o eliminado, comuníquese con el director de turno';
      App::redirect('/salir');

      exit;
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
        $_SESSION['mensajes.advertencia'] = $ultimoPeriodo === false
         ? 'Por favor aperture un período escolar'
         : "Período $ultimoPeriodo excedido, debe aperturar un nuevo período escolar";

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

function permitirEditarBoletinesSoloDelDocenteAutenticado(): callable {
  return function (): void {
    $usuarioAutenticado = obtenerComo(App::view()->get('usuario'), Usuario::class);
    $idDelBoletin = App::router()->current()->params['id'];

    $boletin = obtenerComo(App::get('contenedor')
      ->get(RepositorioDeBoletines::class)
      ->buscar($idDelBoletin), Boletin::class);

    if (
      $boletin->puedeSerEditadoPor($usuarioAutenticado)
      || $usuarioAutenticado->rol() === Rol::Director
    ) {
      return;
    } else {
      $_SESSION['mensajes.error'] = 'No tienes permisos para editar este boletín';
      exit(App::redirect(App::request()->referrer));
    }
  };
}
