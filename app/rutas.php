<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;

require_once __DIR__ . '/intermediarios.php';

App::group('/api', require __DIR__ . '/rutas/api.php');

App::route('GET /salir', function (): void {
  unset($_SESSION['usuario.id']);
  unset($_SESSION['usuario.recordar']);

  App::redirect('/');
});

App::group('/registrate', require __DIR__ . '/rutas/registro-director.php', [
  permitirSiNoHayDirectoresActivos()
]);

App::post('/ingresar', function (): void {
  $credenciales = App::request()->data->getData();

  $sentencia = bd()->prepare(<<<sql
    SElECT id, clave, esta_activo FROM usuarios WHERE cedula = ?
  sql);

  $sentencia->execute([$credenciales['cedula']]);
  $usuarioEncontrado = $sentencia->fetch() ?: ['clave' => ''];

  if (!password_verify($credenciales['clave'], $usuarioEncontrado['clave'])) {
    $_SESSION['mensajes.error'] = 'Cédula o contraseña incorrecta';
    App::redirect('/');

    exit;
  } elseif (!$usuarioEncontrado['esta_activo']) {
    $_SESSION['mensajes.error'] = 'Este usuario se encuentra desactivado';
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

    $cantidadDeMaestros = (int) bd()
      ->query("SELECT COUNT(id) FROM usuarios WHERE rol = 'Docente'")
      ->fetchColumn();

    $cantidadDeSalas = (int) bd()
      ->query("SELECT COUNT(id) FROM salas")
      ->fetchColumn();

    $cantidadDeEstudiantes = (int) bd()
      ->query("SELECT COUNT(id) FROM estudiantes")
      ->fetchColumn();

    $ultimoPeriodo = bd()->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC LIMIT 1
    ")->fetchObject(Periodo::class) ?: null;

    $ultimoMomento = null;

    if ($ultimoPeriodo instanceof Periodo) {
      $mesActual = (int) date('m');

      $ultimoMomento = bd()->query("
        SELECT id, numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio, fecha_registro as fechaRegistro,
        id_periodo as idPeriodo
        FROM momentos
        WHERE idPeriodo = '{$ultimoPeriodo->id}'
        AND mesInicio >= $mesActual
        ORDER BY mesInicio ASC
        LIMIT 1
      ")->fetchObject(Momento::class);
    }

    if ($ultimoMomento === false) {
      $ultimoMomento = null;
    }

    App::render(
      'paginas/inicio',
      compact(
        'cantidadDeUsuarios',
        'cantidadDeRepresentantes',
        'cantidadDeMaestros',
        'cantidadDeEstudiantes',
        'ultimoPeriodo',
        'ultimoMomento',
        'cantidadDeSalas',
      ),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Inicio']);
  });

  $router->get('respaldar', function (): void {
    if (strtolower($_ENV['DB_CONNECTION']) === 'mysql') {
      $backupPath = dirname(__DIR__) . '/base de datos/backups/backup.mysql.sql';

      `{$_ENV['MYSQLDUMP_PATH']} --user={$_ENV['DB_USERNAME']} --password={$_ENV['DB_PASSWORD']} {$_ENV['DB_DATABASE']} > '$backupPath'`;
    } elseif (strtolower($_ENV['DB_CONNECTION']) === 'sqlite') {
      copy($_ENV['DB_DATABASE'], $_ENV['DB_DATABASE'] . '.backup');
    }

    $_SESSION['mensajes.exito'] = 'Base de datos respaldada exitósamente';
    App::redirect('/');
  })->addMiddleware(autorizar(Rol::Director));

  $router->get('restaurar', function (): void {
    if (strtolower($_ENV['DB_CONNECTION']) === 'mysql') {
      $queries = explode(
        ';',
        file_get_contents(__DIR__ . '/../base de datos/backups/backup.mysql.sql')
      );

      foreach ($queries as $query) {
        bd()->query($query);
      }
    } elseif (strtolower($_ENV['DB_CONNECTION']) === 'sqlite') {
      bd(cerrar: true);
      unlink($_ENV['DB_DATABASE']);
      rename($_ENV['DB_DATABASE'] . '.backup', $_ENV['DB_DATABASE']);
    }

    $_SESSION['mensajes.exito'] = 'Base de datos restaurada exitósamente';
    App::redirect('/');
  })->addMiddleware(autorizar(Rol::Director));

  $router->group('usuarios', require __DIR__ . '/rutas/usuarios.php');
  $router->group('representantes', require __DIR__ . '/rutas/representantes.php');
  $router->group('maestros', require __DIR__ . '/rutas/maestros.php');

  $router->group('periodos', require __DIR__ . '/rutas/periodos.php', [
    autorizar(Rol::Director)
  ]);

  $router->group('perfil', require __DIR__ . '/rutas/perfil.php');

  $router->group('salas', require __DIR__ . '/rutas/salas.php', [
    autorizar(Rol::Director, Rol::Secretario)
  ]);

  $router->group('aulas', require __DIR__ . '/rutas/aulas.php', [
    autorizar(Rol::Director, Rol::Secretario)
  ]);

  $router->group('estudiantes', require __DIR__ . '/rutas/estudiantes.php');
  $router->group('inscripciones', require __DIR__ . '/rutas/inscripciones.php');
}, [
  mostrarFormularioDeIngresoSiNoEstaAutenticado(),
  permitirUsuariosActivos(),
  notificarSiLimiteDePeriodoExcedido()
]);
