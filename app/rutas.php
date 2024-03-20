<?php

use Leaf\Auth;
use Leaf\Form;
use Leaf\Http\Request;
use Leaf\Http\Response;
use Leaf\Http\Session;
use Leaf\Router;
use SARCO\Middlewares\Autenticacion;
use SARCO\Middlewares\Mensajes;
use SARCO\Modelos\Representante;
use SARCO\Modelos\Sexo;

Router::all('/salir', function (): void {
  Auth::logout('./');
});

Router::post('/ingresar', function (): void {
  $credenciales = Request::validate([
    'cedula' => 'number',
    'clave' => 'alphadash'
  ]);

  if ($errors = Form::errors()) {
    @$errors['cedula'] && Session::set('error', $errors['cedula'][0]);
    @$errors['clave'] && Session::set('error', $errors['clave'][0]);

    exit(Router::push('./'));
  }

  $usuario = Auth::login($credenciales);

  if (!$usuario) {
    Session::set('error', 'Cédula o contraseña incorrecta');
    exit(Router::push('./'));
  }

  Session::set('credenciales.cedula', $usuario['user']['cedula']);
  Session::set('credenciales.clave', $credenciales['clave']);
  exit(Router::push('./'));
});

Router::post('/registrate', function (): void {
  $info = Request::validate([
    'nombre' => 'textonly',
    'apellido' => 'textonly',
    'cedula' => 'number',
    'clave' => 'alphadash',
    'id_rol' => 'number'
  ]);

  if ($errors = Form::errors()) {
    @$errors['nombre'] && Session::set('error', $errors['nombre'][0]);
    @$errors['apellido'] && Session::set('error', $errors['apellido'][0]);
    @$errors['cedula'] && Session::set('error', $errors['cedula'][0]);
    @$errors['clave'] && Session::set('error', $errors['clave'][0]);
    @$errors['id_rol'] && Session::set('error', $errors['id_rol'][0]);

    exit(Router::push('./'));
  }

  Auth::register($info);
  Session::set('success', 'Cuenta creada exitósamente');
  Router::push('./');
});

Router::group(
  '/',
  ['middleware' => function (): void {
    Mensajes::capturarMensajes();
    Autenticacion::redirigeAlRegistroSiNoHayUsuarios();
    Autenticacion::bloquearNoAutenticados();
  }, function (): void {
    Router::get('/', function (): void {
      $cantidadDeUsuarios = db()->select('usuarios')->count();
      $cantidadDeRepresentantes = db()->select('representantes')->count();

      renderizar(
        'inicio',
        'Inicio',
        'principal',
        compact('cantidadDeUsuarios', 'cantidadDeRepresentantes')
      );
    });

    Router::get('/maestros', function (): void {
    });

    Router::get('/representantes', function (): void {
      $representantes = array_map(function (array $representante): Representante {
        return new Representante(
          $representante['id'],
          $representante['cedula'],
          $representante['nombres'],
          $representante['apellidos'],
          Sexo::from($representante['sexo']),
          new DateTimeImmutable($representante['fecha_nacimiento']),
          $representante['telefono'],
          $representante['correo'],
          $representante['direccion'],
          new DateTimeImmutable($representante['fecha_registro'])
        );
      }, db()->select('representantes')->all());

      renderizar('listado-representantes', 'Representantes', 'principal', compact('representantes'));
    });

    Router::get('/usuarios', function (): void {
    });

    Router::get('/estudiantes', function (): void {
    });

    Router::get('/periodos', function (): void {
    });

    Router::get('/momentos', function (): void {
    });

    Router::get('/salas', function (): void {
    });

    Router::get('/salas/registrar', function (): void {
    });

    Router::get('/momentos/registrar', function (): void {
    });

    Router::get('/periodos/registrar', function (): void {
    });

    Router::get('/estudiantes/inscribir', function (): void {
      renderizar('inscribir', 'Inscribir estudiante', 'principal');
    });

    Router::get('/usuarios/registrar', function (): void {
    });

    Router::get('/representantes/nuevo', function (): void {
      renderizar('nuevo-representante', 'Nuevo representante', 'principal');
    });

    Router::post('/representantes/nuevo', function (): void {
      $info = Request::body();

      db()->insert('representantes')->params([
        'cedula' => $info['cedula'],
        'nombres' => $info['fullname'],
        'apellidos' => $info['lastname'],
        'sexo' => $info['sexo'],
        'fecha_nacimiento' => $info['dob'],
        'telefono' => $info['phone'],
        'correo' => $info['email'],
        'direccion' => $info['address']
      ])->execute();

      Session::set('success', 'Representante registrado exitósamente');
      Router::push('./');
    });

    Router::get('/maestros/registrar', function (): void {
    });

    Router::get('/asignar', function (): void {
      renderizar('asignaciones', 'Asignar estudiante', 'principal');
    });

    Router::post('/asignar', function (): void {
    });

    Router::all('/respaldar', function (): void {
      if ($_ENV['DB_CONNECTION'] === 'mysql') {
        $backupPath = __DIR__ . '/backups/backup.mysql.sql';
        `{$_ENV['MYSQLDUMP_PATH']} --user={$_ENV['DB_USERNAME']} --password={$_ENV['DB_PASSWORD']} {$_ENV['DB_DATABASE']} > $backupPath`;
      }

      Session::set('success', 'Base de datos respaldada exitósamente');
      Router::push('./');
    });

    Router::all('/restaurar', function (): void {
      if ($_ENV['DB_CONNECTION'] === 'mysql') {
        $queries = explode(';', file_get_contents(__DIR__ . '/backups/backup.mysql.sql'));

        foreach ($queries as $query) {
          db()->query($query)->execute();
        }
      }

      Session::set('success', 'Base de datos restaurada exitósamente');
      Router::push('./');
    });
  }]
);

Router::set404(function (): void {
  Mensajes::capturarMensajes();
  renderizar('404', '404 ~ No encontrado', 'errores');
});
