<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\EstadoCivil;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Nacionalidad;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Inscripcion;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Representante;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Usuario;
use Symfony\Component\Uid\UuidV4;

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

App::group('/api', function (Router $router): void {
  $router->get(
    '/asignaciones/@idPeriodo/@fechaNacimiento:\d{4}-\d{2}-\d{2}',
    function (string $idPeriodo, string $fechaNacimiento): void {
      $sentencia = bd()->prepare('
        SELECT s.id as idSala, nombre as nombreSala,
        edad_minima as edadMinima, edad_maxima as edadMaxima
        FROM salas s
        JOIN asignaciones_de_salas a
        ON a.id_sala = s.id
        WHERE id_periodo = ?
      ');

      $sentencia->execute([$idPeriodo]);
      $salas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
      $edad = Estudiante::calcularEdad($fechaNacimiento);

      $salas = array_filter($salas, function (array $sala) use ($edad): bool {
        return $edad >= $sala['edadMinima'] && $edad <= $sala['edadMaxima'];
      });

      App::json($salas);
    }
  );
});

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
    $clave = password_hash($usuario['clave'], PASSWORD_DEFAULT);
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

    $sentencia->bindValue(':id', $id);
    $sentencia->bindValue(':nombres', $usuario['nombres']);
    $sentencia->bindValue(':apellidos', $usuario['apellidos']);
    $sentencia->bindValue(':cedula', $usuario['cedula'], PDO::PARAM_INT);
    $sentencia->bindValue(':fechaNacimiento', $usuario['fecha_nacimiento']);
    $sentencia->bindValue(':genero', $usuario['genero']);
    $sentencia->bindValue(':telefono', $usuario['telefono']);
    $sentencia->bindValue(':correo', $usuario['correo']);
    $sentencia->bindValue(':direccion', $usuario['direccion']);
    $sentencia->bindValue(':clave', $clave);
    $sentencia->bindValue(':rol', Rol::Director->value);

    $sentencia->execute();
    $_SESSION['mensajes.exito'] = 'Director registrado existósamente';
    $_SESSION['usuario.id'] = $id;
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
        'cantidadDeSalas'
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

  $router->group('usuarios', function (Router $router): void {
    $router->get('/', function (): void {
      $idAutenticado = App::view()->get('usuario')->id;

      $usuarios = bd()
        ->query("
          SELECT id, nombres, apellidos, cedula,
          fecha_nacimiento as fechaNacimiento, direccion, telefono, correo,
          rol, esta_activo as estaActivo, fecha_registro as fechaRegistro
          FROM usuarios
          WHERE id != '$idAutenticado'
        ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

      App::render('paginas/usuarios/listado', compact('usuarios'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Usuarios']);
    });

    $router->post('/', function (): void {
      $usuario = App::request()->data->getData();
      $genero = Genero::from($usuario['genero']);
      $rol = Rol::from($usuario['rol']);
      $clave = password_hash($usuario['clave'], PASSWORD_DEFAULT);

      $sentencia = bd()->prepare("
        INSERT INTO usuarios (
          id, nombres, apellidos, cedula, fecha_nacimiento, genero, telefono,
          correo, direccion, clave, rol
        ) VALUES (
          :id, :nombres, :apellidos, :cedula, :fechaNacimiento, :genero,
          :telefono, :correo, :direccion, :clave, :rol
        )
      ");

      $sentencia->bindValue(':id', new UuidV4);
      $sentencia->bindValue(':nombres', $usuario['nombres']);
      $sentencia->bindValue(':apellidos', $usuario['apellidos']);
      $sentencia->bindValue(':cedula', $usuario['cedula'], PDO::PARAM_INT);
      $sentencia->bindValue(':fechaNacimiento', $usuario['fecha_nacimiento']);
      $sentencia->bindValue(':genero', $genero->value);
      $sentencia->bindValue(':telefono', $usuario['telefono']);
      $sentencia->bindValue(':correo', $usuario['correo']);
      $sentencia->bindValue(':direccion', $usuario['direccion']);
      $sentencia->bindValue(':clave', $clave);
      $sentencia->bindValue(':rol', $rol->value);

      try {
        $sentencia->execute();

        $mensaje = $genero === Genero::Femenino ? 'registrada' : 'registrado';
        $_SESSION['mensajes.exito'] = "{$rol->obtenerPorGenero($genero)} $mensaje exitósamente";
        unset($_SESSION['datos']);

        App::redirect('/usuarios');
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

        $_SESSION['datos'] = $usuario;
        App::redirect(App::request()->referrer);
      }
    })->addMiddleware(autorizar(Rol::Director, Rol::Secretario));

    $router->get('/nuevo', function (): void {
      App::render('paginas/usuarios/nuevo', [], 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Nuevo usuario']);
    })->addMiddleware(autorizar(Rol::Director, Rol::Secretario));

    $router->group('/@cedula:[0-9]{7,8}', function (Router $router): void {
      $router->get('/activar', function (int $cedula): void {
        bd()->query("UPDATE usuarios SET esta_activo = TRUE WHERE cedula = $cedula");
        $_SESSION['mensajes.exito'] = 'Usuario activado existósamente';
        App::redirect(App::request()->referrer);
      });

      $router->get('/desactivar', function (int $cedula): void {
        bd()->query("UPDATE usuarios SET esta_activo = FALSE WHERE cedula = $cedula");
        $_SESSION['mensajes.exito'] = 'Usuario desactivado existósamente';
        App::redirect(App::request()->referrer);
      });
    }, [autorizar(Rol::Director)]);
  });

  $router->group('representantes', function (Router $router): void {
    $router->get('/', function (): void {
      $representantes = bd()
        ->query("
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
        $representante = bd()
          ->query("
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

        $sentencia = bd()
          ->prepare("
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
  });

  $router->group('maestros', function (Router $router): void {
    $router->get('/', function (): void {
      $idAutenticado = App::view()->get('usuario')->id;

      $maestros = bd()->query("
        SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
        direccion, telefono, correo, rol, esta_activo as estaActivo,
        fecha_registro as fechaRegistro
        FROM usuarios WHERE rol = 'Docente' AND id != '$idAutenticado'
      ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

      App::render('paginas/maestros/listado', compact('maestros'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Maestros']);
    });
  });

  $router->group('periodos', function (Router $router): void {
    $router->get('/', function (): void {
      $periodos = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos ORDER BY inicio DESC
      ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

      foreach ($periodos as $periodo) {
        $momentos = bd()->query("
          SELECT m.id, numero, mes_inicio as mesInicio,
          dia_inicio as diaInicio,
          mes_cierre as mesCierre,
          dia_cierre as diaCierre,
          m.fecha_registro as fechaRegistro
          FROM momentos m
          JOIN periodos p
          ON id_periodo = p.id
          WHERE id_periodo = '{$periodo->id}'
          ORDER BY numero
        ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

        $periodo->asignarMomentos(...$momentos);
      }

      App::render('paginas/periodos/listado', compact('periodos'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Períodos']);
    });

    $router->post('/', function (): void {
      $periodo = App::request()->data->getData();
      $añoInicio = (int) $periodo['anio_inicio'];
      $momentos = $periodo['momentos'];

      bd()->beginTransaction();

      try {
        $momentos = array_map(function (array $momento): array {
          [$fechaInicio, $mesInicio, $diaInicio] = explode('-', $momento['inicio']);
          [$fechaCierre, $mesCierre, $diaCierre] = explode('-', $momento['fin']);

          return [
            'inicio' => [
              'año' => $fechaInicio,
              'mes' => $mesInicio,
              'dia' => $diaInicio
            ],
            'cierre' => [
              'año' => $fechaCierre,
              'mes' => $mesCierre,
              'dia' => $diaCierre
            ],
            'inicioCompleto' => $momento['inicio'],
            'cierreCompleto' => $momento['fin'],
            'id' => new UuidV4
          ];
        }, $momentos);

        if (
          $momentos[1]['inicio']['año'] != $añoInicio
          || $momentos[1]['cierre']['año'] != $añoInicio
          || $momentos[2]['inicio']['año'] != $añoInicio
          || $momentos[2]['cierre']['año'] != $añoInicio
          || $momentos[3]['inicio']['año'] != $añoInicio
          || $momentos[3]['cierre']['año'] != $añoInicio
        ) {
          throw new Error("Los momentos deben del año $añoInicio");
        } elseif (
          $momentos[1]['inicioCompleto'] >= $momentos[1]['cierreCompleto']
        ) {
          throw new Error('El inicio del 1er Momento debe ser antes del fin');
        } elseif (
          $momentos[1]['cierreCompleto'] >= $momentos[2]['inicioCompleto']
        ) {
          throw new Error('El fin del 1er Momento debe ser antes del inicio del 2do Momento');
        } elseif (
          $momentos[2]['inicioCompleto'] >= $momentos[2]['cierreCompleto']
        ) {
          throw new Error('El inicio del 2do Momento debe ser antes del fin');
        } elseif (
          $momentos[2]['cierreCompleto'] >= $momentos[3]['inicioCompleto']
        ) {
          throw new Error('El fin del 2do Momento debe ser antes del inicio del 3er Momento');
        } elseif (
          $momentos[3]['inicioCompleto'] >= $momentos[3]['cierreCompleto']
        ) {
          throw new Error('El inicio del 3er Momento debe ser antes del fin');
        }

        $idPeriodo = new UuidV4;

        bd()->query("
          INSERT INTO periodos (id, anio_inicio)
          VALUES ('$idPeriodo', $añoInicio)
        ");

        $sentencia = bd()->prepare("
          INSERT INTO momentos (id, numero, mes_inicio, dia_inicio, mes_cierre,
          dia_cierre, id_periodo) VALUES (:id, :numero, :mesInicio, :diaInicio,
          :mesCierre, :diaCierre, '$idPeriodo')
        ");

        foreach ($momentos as $numero => $momento) {
          $sentencia->bindValue(':id', $momento['id']);
          $sentencia->bindValue(':numero', $numero, PDO::PARAM_INT);
          $sentencia->bindValue(':mesInicio', $momento['inicio']['mes'], PDO::PARAM_INT);
          $sentencia->bindValue(':diaInicio', $momento['inicio']['dia'], PDO::PARAM_INT);
          $sentencia->bindValue(':mesCierre', $momento['cierre']['mes'], PDO::PARAM_INT);
          $sentencia->bindValue(':diaCierre', $momento['cierre']['dia'], PDO::PARAM_INT);

          $sentencia->execute();
        }

        bd()->commit();
        $_SESSION['mensajes.exito'] = "Período $añoInicio aperturado exitósamente";
        unset($_SESSION['datos']);
        App::redirect('/periodos');

        return;
      } catch (PDOException $error) {
        if (str_contains($error, 'periodos.anio_inicio')) {
          $_SESSION['mensajes.error'] = "Periodo $añoInicio ya fue aperturado";
        } else {
          throw $error;
        }
      } catch (Error $error) {
        $_SESSION['mensajes.error'] = $error->getMessage();
      }

      bd()->rollBack();
      $_SESSION['datos'] = $periodo;
      App::redirect(App::request()->referrer);
    });

    $router->get('/nuevo', function (): void {
      $ultimoPeriodo = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos
        ORDER BY inicio DESC LIMIT 1
      ")->fetchObject(Periodo::class) ?: null;

      App::render('paginas/periodos/nuevo', compact('ultimoPeriodo'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Nuevo período']);
    });

    $router->get('/@periodo:[0-9]{4}/editar', function (int $periodo): void {
      $periodo = bd()
        ->query("
          SELECT p.id, p.anio_inicio as inicio, p.fecha_registro as fechaRegistro
          FROM periodos p
          WHERE inicio = $periodo
        ")->fetchObject(Periodo::class);

      $momentos = bd()->query("
        SELECT m.id, numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio,
        mes_cierre as mesCierre,
        dia_cierre as diaCierre,
        m.fecha_registro as fechaRegistro
        FROM momentos m
        JOIN periodos p
        ON id_periodo = p.id
        WHERE id_periodo = '{$periodo->id}'
        ORDER BY numero
      ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

      $periodo->asignarMomentos(...$momentos);

      App::render('paginas/periodos/editar', compact('periodo'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Editar período']);
    });

    $router->post('/@periodo:[0-9]{4}/', function (int $viejoAñoInicio): void {
      $periodo = App::request()->data->getData();
      $idPeriodo = $periodo['id_periodo'];
      $añoInicio = (int) $periodo['anio_inicio'];
      $momentos = $periodo['momentos'];

      bd()->beginTransaction();

      try {
        $momentos = array_map(function (array $momento): array {
          [$fechaInicio, $mesInicio, $diaInicio] = explode('-', $momento['inicio']);
          [$fechaCierre, $mesCierre, $diaCierre] = explode('-', $momento['fin']);

          return [
            'inicio' => [
              'año' => $fechaInicio,
              'mes' => $mesInicio,
              'dia' => $diaInicio
            ],
            'cierre' => [
              'año' => $fechaCierre,
              'mes' => $mesCierre,
              'dia' => $diaCierre
            ],
            'inicioCompleto' => $momento['inicio'],
            'cierreCompleto' => $momento['fin'],
          ];
        }, $momentos);

        if (
          $momentos[1]['inicio']['año'] != $añoInicio
          || $momentos[1]['cierre']['año'] != $añoInicio
          || $momentos[2]['inicio']['año'] != $añoInicio
          || $momentos[2]['cierre']['año'] != $añoInicio
          || $momentos[3]['inicio']['año'] != $añoInicio
          || $momentos[3]['cierre']['año'] != $añoInicio
        ) {
          throw new Error("Los momentos deben del año $añoInicio");
        } elseif (
          $momentos[1]['inicioCompleto'] >= $momentos[1]['cierreCompleto']
        ) {
          throw new Error('El inicio del 1er Momento debe ser antes del fin');
        } elseif (
          $momentos[1]['cierreCompleto'] >= $momentos[2]['inicioCompleto']
        ) {
          throw new Error('El fin del 1er Momento debe ser antes del inicio del 2do Momento');
        } elseif (
          $momentos[2]['inicioCompleto'] >= $momentos[2]['cierreCompleto']
        ) {
          throw new Error('El inicio del 2do Momento debe ser antes del fin');
        } elseif (
          $momentos[2]['cierreCompleto'] >= $momentos[3]['inicioCompleto']
        ) {
          throw new Error('El fin del 2do Momento debe ser antes del inicio del 3er Momento');
        } elseif (
          $momentos[3]['inicioCompleto'] >= $momentos[3]['cierreCompleto']
        ) {
          throw new Error('El inicio del 3er Momento debe ser antes del fin');
        }

        $sentencia = bd()->prepare("
          UPDATE periodos SET anio_inicio = :inicio
          WHERE id = :id
        ");

        $sentencia->execute([':inicio' => $añoInicio, ':id' => $idPeriodo]);

        $sentencia = bd()->prepare("
          UPDATE momentos SET mes_inicio = :mesInicio, dia_inicio = :diaInicio,
          mes_cierre = :mesCierre, dia_cierre = :diaCierre
          WHERE numero = :numero AND id_periodo = :idPeriodo
        ");

        foreach ($momentos as $numero => $momento) {
          $sentencia->execute([
            ':mesInicio' => $momento['inicio']['mes'],
            ':diaInicio' => $momento['inicio']['dia'],
            ':mesCierre' => $momento['cierre']['mes'],
            ':diaCierre' => $momento['cierre']['dia'],
            ':numero' => $numero,
            ':idPeriodo' => $idPeriodo
          ]);
        }

        bd()->commit();
        $_SESSION['mensajes.exito'] = "Período $viejoAñoInicio actualizado exitósamente";
        unset($_SESSION['datos']);
        App::redirect('/periodos');

        return;
      } catch (PDOException $error) {
        if (str_contains($error, 'periodos.anio_inicio')) {
          $_SESSION['mensajes.error'] = "Periodo $añoInicio ya fue aperturado";
        } else {
          throw $error;
        }
      } catch (Error $error) {
        $_SESSION['mensajes.error'] = $error->getMessage();
      }

      bd()->rollBack();
      $_SESSION['datos'] = $periodo;
      App::redirect(App::request()->referrer);
    });
  }, [autorizar(Rol::Director)]);

  $router->group('perfil', function (Router $router): void {
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
  });

  $router->group('salas', function (Router $router): void {
    $router->get('/', function (): void {
      $salas = bd()->query("
        SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
      ")->fetchAll(PDO::FETCH_CLASS, Sala::class);

      App::render('paginas/salas/listado', compact('salas'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Salas']);
    });

    $router->post('/', function (): void {
      $sala = App::request()->data->getData();

      $sentencia = bd()->prepare('
        INSERT INTO salas (id, nombre, edad_minima, edad_maxima)
        VALUES (:id, :nombre, :edadMinima, :edadMaxima)
      ');

      $sentencia->bindValue(
        ':nombre',
        mb_convert_case($sala['nombre'], MB_CASE_TITLE)
      );

      $sentencia->bindValue(':id', new UuidV4);
      $sentencia->bindValue(':edadMinima', $sala['edad_minima'], PDO::PARAM_INT);
      $sentencia->bindValue(':edadMaxima', $sala['edad_maxima'], PDO::PARAM_INT);

      try {
        $sentencia->execute();
        $_SESSION['mensajes.exito'] = "Sala {$sala['nombre']} aperturada exitósamente";
        App::redirect('/salas');
      } catch (PDOException $error) {
        if (str_contains($error, 'salas.nombre')) {
          $_SESSION['mensajes.error'] = "Sala {$sala['nombre']} ya existe";
        } else {
          throw $error;
        }

        App::redirect('/salas/nueva');
      }
    });

    $router->get('/nueva', function (): void {
      App::render('paginas/salas/nueva', [], 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Aperturar sala']);
    });

    $router->get('/asignar', function (): void {
      $periodos = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos ORDER BY inicio DESC
      ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

      $periodoActual = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos ORDER BY inicio DESC LIMIT 1
      ")->fetchObject(Periodo::class) ?: null;

      $idAutenticado = App::view()->get('usuario')->id;

      $maestros = bd()->query("
        SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
        direccion, telefono, correo, rol, esta_activo as estaActivo,
        fecha_registro as fechaRegistro
        FROM usuarios WHERE rol = 'Docente' AND id != '$idAutenticado'
      ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

      $salas = bd()->query("
        SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
      ")->fetchAll(PDO::FETCH_CLASS, Sala::class);

      $aulas = bd()->query("
        SELECT id, codigo, fecha_registro as fechaRegistro, tipo FROM aulas
      ")->fetchAll(PDO::FETCH_CLASS, Aula::class);

      App::render(
        'paginas/salas/asignar',
        compact('periodos', 'periodoActual', 'maestros', 'salas', 'aulas'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => 'Asignar maestros a sala']);
    });

    $router->post('/asignar', function (): void {
      $asignacion = App::request()->data->getData();

      $sentencia = bd()->prepare("
        INSERT INTO asignaciones_de_salas (id, id_sala, id_aula, id_periodo,
        id_docente1, id_docente2, id_docente3) VALUES (:id, :idSala, :idAula,
        :idPeriodo, :idDocente1, :idDocente2, :idDocente3)
      ");

      $sentencia->execute([
        ':id' => new UuidV4,
        ':idSala' => $asignacion['id_sala'],
        ':idAula' => $asignacion['id_aula'],
        ':idPeriodo' => $asignacion['id_periodo'],
        ':idDocente1' => $asignacion['id_maestro'][1],
        ':idDocente2' => $asignacion['id_maestro'][2],
        ':idDocente3' => $asignacion['id_maestro'][3] ?? null,
      ]);

      $_SESSION['mensajes.exito'] = 'Maestros asignados exitósamente';
      App::redirect('/');
    });

    $router->group('/@id', function (Router $router): void {
      $router->get('/', function (string $id): void {
        $sala = bd()->query("
          SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
          esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
          WHERE id = '$id'
        ")->fetchObject(Sala::class);

        App::render('paginas/salas/editar', compact('sala'), 'pagina');
        App::render('plantillas/privada', ['titulo' => 'Editar sala']);
      });

      $router->post('/', function (string $id): void {
        $sala = App::request()->data->getData();

        $sentencia = bd()->prepare("
          UPDATE salas SET nombre = :nombre, edad_minima = :edadMinima,
          edad_maxima = :edadMaxima WHERE id = '$id'
        ");

        $sentencia->bindValue(':nombre', $sala['nombre']);
        $sentencia->bindValue(':edadMinima', $sala['edad_minima'], PDO::PARAM_INT);
        $sentencia->bindValue(':edadMaxima', $sala['edad_maxima'], PDO::PARAM_INT);

        try {
          $sentencia->execute();
          $_SESSION['mensajes.exito'] = 'Sala actualizada exitósamente';
          App::redirect('/salas');
        } catch (PDOException $error) {
          if (str_contains($error, 'salas.nombre')) {
            $_SESSION['mensajes.error'] = "Sala {$sala['nombre']} ya existe";
          } else {
            throw $error;
          }

          App::redirect("/salas/$id");
        }
      });

      $router->get('/habilitar', function (string $id): void {
        bd()->query("UPDATE salas SET esta_activa = TRUE WHERE id = '$id'");
        $_SESSION['mensajes.exito'] = 'Sala habilitada exitósamente';
        App::redirect('/salas');
      });

      $router->get('/inhabilitar', function (string $id): void {
        bd()->query("UPDATE salas SET esta_activa = FALSE WHERE id = '$id'");
        $_SESSION['mensajes.exito'] = 'Sala inhabilitada exitósamente';
        App::redirect('/salas');
      });
    });
  }, [autorizar(Rol::Director, Rol::Secretario)]);

  $router->group('estudiantes', function (Router $router): void {
    $router->get('/', function (): void {
      $estudiantes = bd()->query("
        SELECT id, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
        genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
        id_mama as idMama, id_papa as idPapa FROM estudiantes
      ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

      App::render('paginas/estudiantes/listado', compact('estudiantes'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Estudiantes']);
    });

    $router->get('/inscribir', function (): void {
      $periodos = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos ORDER BY inicio DESC
      ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

      $periodoActual = bd()->query("
        SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
        FROM periodos ORDER BY inicio DESC LIMIT 1
      ")->fetchObject(Periodo::class) ?: null;

      App::render(
        'paginas/estudiantes/inscribir',
        compact('periodos', 'periodoActual'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => 'Inscribir estudiante']);
    })->addMiddleware(autorizar(Rol::Secretario));

    $router->post('/inscribir', function (): void {
      $inscripcion = App::request()->data->getData();

      bd()->beginTransaction();

      try {
        $sentencia = bd()->prepare("
          INSERT INTO representantes (nombres, apellidos, cedula,
          fecha_nacimiento, estado_civil, nacionalidad, telefono, correo)
          VALUES (:nombres, :apellidos, :cedula, :fechaNacimiento, :estadoCivil,
          :nacionalidad, :telefono, :correo)
        ");

        if (!empty($inscripcion['padre']['nombres'])) {
          $estadoCivil = EstadoCivil::from($inscripcion['padre']['estado_civil'])
            ->obtenerPorGenero(Genero::Masculino);

          $nacionalidad = Nacionalidad::from($inscripcion['padre']['nacionalidad'])
            ->obtenerPorGenero(Genero::Masculino);

          $sentencia->bindValue(':nombres', $inscripcion['padre']['nombres']);
          $sentencia->bindValue(':apellidos', $inscripcion['padre']['apellidos']);
          $sentencia->bindValue(':cedula', $inscripcion['padre']['cedula'], PDO::PARAM_INT);
          $sentencia->bindValue(':fechaNacimiento', $inscripcion['padre']['fecha_nacimiento']);
          $sentencia->bindValue(':estadoCivil', $estadoCivil);
          $sentencia->bindValue(':nacionalidad', $nacionalidad);
          $sentencia->bindValue(':telefono', $inscripcion['padre']['telefono']);
          $sentencia->bindValue(':correo', $inscripcion['padre']['correo']);

          $sentencia->execute();
          $idDelPapa = bd()->lastInsertId();
        }

        $estadoCivil = EstadoCivil::from($inscripcion['madre']['estado_civil'])
          ->obtenerPorGenero(Genero::Femenino);

        $nacionalidad = Nacionalidad::from($inscripcion['madre']['nacionalidad'])
          ->obtenerPorGenero(Genero::Femenino);

        $sentencia->bindValue(':nombres', $inscripcion['madre']['nombres']);
        $sentencia->bindValue(':apellidos', $inscripcion['madre']['apellidos']);
        $sentencia->bindValue(':cedula', $inscripcion['madre']['cedula'], PDO::PARAM_INT);
        $sentencia->bindValue(':fechaNacimiento', $inscripcion['madre']['fecha_nacimiento']);
        $sentencia->bindValue(':estadoCivil', $estadoCivil);
        $sentencia->bindValue(':nacionalidad', $nacionalidad);
        $sentencia->bindValue(':telefono', $inscripcion['madre']['telefono']);
        $sentencia->bindValue(':correo', $inscripcion['madre']['correo']);

        $sentencia->execute();
        $idDeLaMama = bd()->lastInsertId();
        $idDelPapa ??= 'NULL';
        [$añoDeNacimiento] = explode('-', $inscripcion['estudiante']['fecha_nacimiento']);
        $ultimosDigitosAñoNacimiento = substr($añoDeNacimiento, 2);
        $cedulaEscolar = "v-1{$ultimosDigitosAñoNacimiento}{$inscripcion['madre']['cedula']}";

        $sentencia = bd()->prepare("
          INSERT INTO estudiantes (nombres, apellidos, cedula_escolar,
          fecha_nacimiento, lugar_nacimiento, genero, tipo_sangre, id_mama,
          id_papa) VALUES (:nombres, :apellidos, :cedula, :fechaNacimiento,
          :lugarNacimiento, :genero, :grupoSanguineo, $idDeLaMama, $idDelPapa)
        ");

        $sentencia->bindValue(':nombres', $inscripcion['estudiante']['nombres']);
        $sentencia->bindValue(':apellidos', $inscripcion['estudiante']['apellidos']);
        $sentencia->bindValue(':cedula', $cedulaEscolar);
        $sentencia->bindValue(':fechaNacimiento', $inscripcion['estudiante']['fecha_nacimiento']);
        $sentencia->bindValue(':lugarNacimiento', $inscripcion['estudiante']['lugar_nacimiento']);
        $sentencia->bindValue(':genero', $inscripcion['estudiante']['genero']);
        $sentencia->bindValue(':grupoSanguineo', $inscripcion['estudiante']['grupo_sanguineo']);
        $sentencia->execute();
        $idDelEstudiante = bd()->lastInsertId();

        $sentencia = bd()->prepare("
          INSERT INTO inscripciones (id_momento, id_estudiante,
          id_asignacion_docente, id_asignacion_asistente) VALUES (:idMomento,
          :idEstudiante, :idAsignacionDocente, :idAsignacionAsistente)
        ");

        $sentencia->bindValue(':idMomento', $inscripcion['id_momento'], PDO::PARAM_INT);
        $sentencia->bindValue(':idEstudiante', $idDelEstudiante, PDO::PARAM_INT);
        $sentencia->bindValue(':idAsignacionDocente', $inscripcion['id_asignacion_docente'], PDO::PARAM_INT);
        $sentencia->bindValue(':idAsignacionAsistente', $inscripcion['id_asignacion_asistente'], PDO::PARAM_INT);
        $sentencia->execute();

        // $consulta = "SELECT a.id_docente FROM asignaciones_de_docentes a
        // JOIN usuarios d
        // ON a.id_docente = d.id
        // WHERE a.id = {$inscripcion['id_asignacion_docente']}";

        // dd($consulta, $inscripcion);

        // $idDelDocente = (int) bd()->query($consulta)->fetchColumn();

        // $idDelAsistente = (int) bd()->query("
        //   SELECT u.id as idDelDocente FROM asignaciones_de_docentes a
        //   JOIN usuarios u ON a.id_docente = u.id
        //   WHERE a.id = {$inscripcion['id_asignacion_asistente']}
        // ")->fetchColumn();

        // $sentencia = bd()->prepare("SELECT id_periodo FROM momentos WHERE id = ?");
        // $sentencia->execute([$inscripcion['id_momento']]);
        // $idPeriodo = $sentencia->fetchColumn();

        // $idsMomentos = bd()
        //   ->query("SELECT id FROM momentos WHERE id_periodo = $idPeriodo")
        //   ->fetchAll();

        // $idsMomentos = array_column($idsMomentos, 'id');

        // $sentenciaBoletines = bd()->prepare("
        //   INSERT INTO boletines (numero_inasistencias, nombre_proyecto,
        //   descripcion_formacion, descripcion_ambiente, recomendaciones,
        //   id_estudiante, id_momento, id_docente, id_asistente) VALUES (
        //   0, 'No establecido', 'No establecida', 'No establecida',
        //   'No establecidas', :idEstudiante, :idMomento, :idDocente, :idAsistente)
        // ");

        // $sentenciaBusquedaBoletin = bd()->prepare("
        //   SELECT COUNT(id) FROM boletines WHERE id_estudiante = ?
        //   AND id_momento = ?
        // ");

        // foreach ($idsMomentos as $idMomento) {
        //   $sentenciaBusquedaBoletin->execute([$idDelEstudiante, $idMomento]);
        //   $noAñadirBoletin = $sentenciaBusquedaBoletin->fetchColumn();

        //   if ($noAñadirBoletin) {
        //     continue;
        //   }

        //   $sentenciaBoletines->bindValue(':idEstudiante', $idDelEstudiante, PDO::PARAM_INT);
        //   $sentenciaBoletines->bindValue(':idMomento', $idMomento, PDO::PARAM_INT);
        //   $sentenciaBoletines->bindValue(':idDocente', $idDelDocente, PDO::PARAM_INT);
        //   $sentenciaBoletines->bindValue(':idAsistente', $idDelAsistente, PDO::PARAM_INT);

        //   dd($sentenciaBoletines->debugDumpParams());
        //   // $sentenciaBoletines->execute();
        // }

        // $sentencia = bd()->query("
        //   INSERT INTO boletines (numero_inasistencias, nombre_proyecto,
        //   descripcion_formacion, descripcion_ambiente, recomendaciones,
        //   id_estudiante, id_momento, id_docente, id_asistente) VALUES (
        //   0, 'No establecido', 'No establecida', 'No establecida',
        //   'No establecidas', $idDelEstudiante, {$inscripcion['id_momento']},
        //   $idDelDocente, $idDelAsistente)
        // ");

        bd()->commit();
        $_SESSION['mensajes.exito'] = 'Estudiante inscrito exitósamente';
        App::redirect('/inscripciones');
      } catch (PDOException $error) {
        bd()->rollBack();

        throw $error;
        App::redirect('/estudiantes/inscribir');
      }
    })->addMiddleware(autorizar(Rol::Secretario));

    $router->get('/boletines', function (): void {
      $idDelDocente = (int) App::view()->get('usuario')->id;

      $boletines = bd()->query("
        SELECT b.id, numero_inasistencias as inasistencias,
        nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
        descripcion_ambiente as descripcionAmbiente, recomendaciones,
        b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
        e.apellidos as apellidosEstudiante, e.cedula_escolar as cedulaEstudiante,
        m.numero_momento as momento FROM boletines b JOIN estudiantes e
        JOIN momentos m ON b.id_estudiante = e.id AND b.id_momento = m.id
        WHERE b.id_docente = $idDelDocente
      ")->fetchAll(PDO::FETCH_CLASS, Boletin::class);

      App::render('paginas/boletines/listado', compact('boletines'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Boletines']);
    })->addMiddleware(Rol::Secretario, Rol::Docente);

    $router->group('/boletines/@id:[0-9]+', function (Router $router): void {
      $router->get('/', function (int $id): void {
        $boletin = bd()->query("
          SELECT b.id, numero_inasistencias as inasistencias,
          nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
          descripcion_ambiente as descripcionAmbiente, recomendaciones,
          b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
          e.apellidos as apellidosEstudiante, e.cedula_escolar as cedulaEstudiante,
          m.numero_momento as momento FROM boletines b JOIN estudiantes e
          JOIN momentos m ON b.id_estudiante = e.id AND b.id_momento = m.id
          WHERE b.id = $id
        ")->fetchObject(Boletin::class);

        App::render('paginas/boletines/editar', compact('boletin'), 'pagina');
        App::render('plantillas/privada', ['titulo' => 'Editar boletín']);
      });

      $router->post('/', function (int $id): void {
        $boletin = App::request()->data->getData();

        $sentencia = bd()->prepare("
          UPDATE boletines SET numero_inasistencias = :inasistencias,
          nombre_proyecto = :proyecto, descripcion_formacion = :formacion,
          descripcion_ambiente = :ambiente, recomendaciones = :recomendaciones
          WHERE id = $id
        ");

        $sentencia->bindValue(':inasistencias', $boletin['inasistencias'], PDO::PARAM_INT);
        $sentencia->bindValue(':proyecto', $boletin['proyecto']);
        $sentencia->bindValue(':formacion', $boletin['formacion']);
        $sentencia->bindValue(':ambiente', $boletin['ambiente']);
        $sentencia->bindValue(':recomendaciones', $boletin['recomendaciones']);

        try {
          $sentencia->execute();
          $_SESSION['mensajes.exito'] = 'Boletín actualizado exitósamente';
          App::redirect('/estudiantes/boletines');
        } catch (PDOException $error) {
          throw $error;
        }
      });
    }, [autorizar(Rol::Docente)]);
  });

  $router->group('inscripciones', function (Router $router): void {
    $router->get('/', function (): void {
      $inscripciones = bd()->query("
        SELECT i.id, i.fecha_registro as fechaRegistro,
        m.numero_momento as momento, e.nombres as nombresEstudiante,
        e.apellidos as apellidosEstudiante, d.nombres as nombresDocente,
        d.apellidos as apellidosDocente
        FROM inscripciones i JOIN momentos m JOIN estudiantes e
        JOIN usuarios d ON i.id_momento = m.id AND i.id_estudiante = e.id
        AND i.id_asignacion_docente = d.id
      ")->fetchAll(PDO::FETCH_CLASS, Inscripcion::class);

      App::render(
        'paginas/inscripciones/listado',
        compact('inscripciones'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => 'Inscripciones']);
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
    fecha_registro as fechaRegistro, clave
    FROM usuarios
    WHERE id = ?
  ');

  $sentencia->execute([$_SESSION['usuario.id']]);
  $usuario = $sentencia->fetchObject(Usuario::class);

  App::view()->set('usuario', $usuario);
}, function (): void {
  $usuario = App::view()->get('usuario');
  assert($usuario instanceof Usuario);

  if (!$usuario->estaActivo) {
    App::redirect('/salir');

    return;
  }
}]);
