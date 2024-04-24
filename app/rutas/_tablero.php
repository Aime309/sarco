<?php

use Illuminate\Container\Container;
use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\UsuarioNoExiste;

Flight::group('/', function (): void {
  Flight::route('GET /', function (): void {
    $cantidadDeUsuarios = 0;
    $cantidadDeRepresentantes = 0;

    renderizar(
      'inicio',
      'Inicio',
      'principal',
      compact('cantidadDeUsuarios', 'cantidadDeRepresentantes')
    );
  });

  Flight::route('GET /maestros', function (): void {
  });

  // Flight::route('GET /representantes', function (): void {
  //   $representantes = array_map(function (array $representante): Representante {
  //     return new Representante(
  //       $representante['id'],
  //       $representante['cedula'],
  //       $representante['nombres'],
  //       $representante['apellidos'],
  //       Sexo::from($representante['sexo']),
  //       new DateTimeImmutable($representante['fecha_nacimiento']),
  //       $representante['telefono'],
  //       $representante['correo'],
  //       $representante['direccion'],
  //       new DateTimeImmutable($representante['fecha_registro'])
  //     );
  //   }, db()->select('representantes')->all());

  //   renderizar('listado-representantes', 'Representantes', 'principal', compact('representantes'));
  // });

  Flight::route('GET /usuarios', function (): void {
  });

  Flight::route('GET /estudiantes', function (): void {
  });

  Flight::route('GET /periodos', function (): void {
  });

  Flight::route('GET /momentos', function (): void {
  });

  Flight::route('GET /salas', function (): void {
  });

  Flight::route('GET /salas/registrar', function (): void {
  });

  Flight::route('GET /momentos/registrar', function (): void {
  });

  Flight::route('GET /periodos/registrar', function (): void {
  });

  Flight::route('GET /estudiantes/inscribir', function (): void {
    renderizar('inscribir', 'Inscribir estudiante', 'principal');
  });

  Flight::route('GET /usuarios/registrar', function (): void {
  });

  Flight::route('GET /representantes/nuevo', function (): void {
    renderizar('nuevo-representante', 'Nuevo representante', 'principal');
  });

  // Flight::post('/representantes/nuevo', function (): void {
  //   $info = Request::body();

  //   db()->insert('representantes')->params([
  //     'cedula' => $info['cedula'],
  //     'nombres' => $info['fullname'],
  //     'apellidos' => $info['lastname'],
  //     'sexo' => $info['sexo'],
  //     'fecha_nacimiento' => $info['dob'],
  //     'telefono' => $info['phone'],
  //     'correo' => $info['email'],
  //     'direccion' => $info['address']
  //   ])->execute();

  //   Session::set('success', 'Representante registrado exitósamente');
  //   Flight::push('./');
  // });

  // Flight::route('GET /representantes/{cedula}/editar', function (string $cedula): void {
  //   $info = db()->select('representantes')->where('cedula', $cedula)->assoc();

  //   renderizar('editar-representante', 'Editar representante', 'principal', ['representante' => new Representante(
  //     $info['id'],
  //     $info['cedula'],
  //     $info['nombres'],
  //     $info['apellidos'],
  //     Sexo::from($info['sexo']),
  //     new DateTimeImmutable($info['fecha_nacimiento']),
  //     $info['telefono'],
  //     $info['correo'],
  //     $info['direccion'],
  //     new DateTimeImmutable($info['fecha_registro'])
  //   )]);
  // });

  // Flight::post('/representantes/{cedula}/editar', function (string $cedula): void {
  //   $info = Request::body();

  //   db()->update('representantes')
  //     ->params([
  //       'nombres' => $info['fullname'],
  //       'apellidos' => $info['lastname'],
  //       'cedula' => $info['cedula'],
  //       'sexo' => $info['sexo'],
  //       'fecha_nacimiento' => $info['dob'],
  //       'telefono' => $info['phone'],
  //       'correo' => $info['email'],
  //       'direccion' => $info['address']
  //     ])
  //     ->execute();

  //   Session::set('success', 'Representante editado exitósamente');
  //   Flight::push('../');
  // });

  Flight::route('GET /maestros/registrar', function (): void {
  });

  Flight::route('GET /asignar', function (): void {
    renderizar('asignaciones', 'Asignar estudiante', 'principal');
  });

  Flight::post('/asignar', function (): void {
  });

  // Flight::all('/respaldar', function (): void {
  //   if ($_ENV['DB_CONNECTION'] === 'mysql') {
  //     $backupPath = __DIR__ . '/backups/backup.mysql.sql';
  //     `{$_ENV['MYSQLDUMP_PATH']} --user={$_ENV['DB_USERNAME']} --password={$_ENV['DB_PASSWORD']} {$_ENV['DB_DATABASE']} > $backupPath`;
  //   }

  //   Session::set('success', 'Base de datos respaldada exitósamente');
  //   Flight::push('./');
  // });

  // Flight::all('/restaurar', function (): void {
  //   if ($_ENV['DB_CONNECTION'] === 'mysql') {
  //     $queries = explode(';', file_get_contents(__DIR__ . '/backups/backup.mysql.sql'));

  //     foreach ($queries as $query) {
  //       db()->query($query)->execute();
  //     }
  //   }

  //   Session::set('success', 'Base de datos restaurada exitósamente');
  //   Flight::push('./');
  // });
}, [
  function (): void {
    $cedula = @$_SESSION['credenciales.cedula'];
    $repositorio = Container::getInstance()->get(RepositorioDeUsuarios::class);

    try {
      Flight::view()->set('usuario', $repositorio->encontrarPorCedula(new Cedula($cedula)));
    } catch (UsuarioNoExiste | TypeError) {
      Flight::redirect('/ingresar');
    }
  }
]);
