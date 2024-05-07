<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\EstadoCivil;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Nacionalidad;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Inscripcion;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Representante;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Usuario;

App::group('/api', function (Router $router): void {
  $router->get('/salas/asignaciones/@idMomento:[0-9]+', function (int $idMomento): void {
    $idPeriodo = (int) bd()->query("
      SELECT id_periodo FROM momentos WHERE id = $idMomento
    ")->fetchColumn();

    $asignaciones = bd()->query("
      SELECT a.id, d.id as idDocente, d.nombres as nombresDocente,
      d.apellidos as apellidosDocente, s.nombre as sala
      FROM asignaciones_de_docentes a
      JOIN usuarios d
      JOIN salas s
      ON a.id_docente = d.id AND a.id_sala = s.id
      WHERE id_periodo = $idPeriodo
    ")->fetchAll();

    App::json(array_map(static fn (array $asignacion): array => [
      'id' => $asignacion['id'],
      'docente' => [
        'id' => $asignacion['idDocente'],
        'nombre' => "{$asignacion['nombresDocente']} {$asignacion['apellidosDocente']}"
      ],
      'sala' => $asignacion['sala']
    ], $asignaciones));
  });
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
        SELECT id, numero_momento as numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio, fecha_registro as fechaRegistro,
        id_periodo as idPeriodo FROM momentos
        WHERE idPeriodo = {$ultimoPeriodo->id}
        AND mesInicio >= $mesActual
        ORDER BY mesInicio ASC
        LIMIT 1
      ")->fetchObject(Momento::class);
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
  });

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
      $rol = Rol::from($usuario['rol'])->obtenerPorGenero($genero);
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

    $router->post('/', function (): void {
      $representante = App::request()->data->getData();
      $genero = Genero::from($representante['genero']);

      $estadoCivil = EstadoCivil::from($representante['estado_civil'])
        ->obtenerPorGenero($genero);

      $nacionalidad = Nacionalidad::from($representante['nacionalidad'])
        ->obtenerPorGenero($genero);

      $sentencia = bd()->prepare("
        INSERT INTO representantes (
          nombres, apellidos, cedula, fecha_nacimiento, estado_civil,
          nacionalidad, telefono, correo
        ) VALUES (
          :nombres, :apellidos, :cedula, :fechaNacimiento, :estadoCivil,
          :nacionalidad, :telefono, :correo
        )
      ");

      $sentencia->bindValue(':nombres', $representante['nombres']);
      $sentencia->bindValue(':apellidos', $representante['apellidos']);
      $sentencia->bindValue(':cedula', $representante['cedula'], PDO::PARAM_INT);
      $sentencia->bindValue(':fechaNacimiento', $representante['fecha_nacimiento']);
      $sentencia->bindValue(':estadoCivil', $estadoCivil);
      $sentencia->bindValue(':nacionalidad', $nacionalidad);
      $sentencia->bindValue(':telefono', $representante['telefono']);
      $sentencia->bindValue(':correo', $representante['correo']);

      try {
        $sentencia->execute();
        $mensaje = $genero === Genero::Femenino ? 'registrada' : 'registrado';
        $_SESSION['mensajes.exito'] = "Representante $mensaje exitósamente";
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

        App::redirect('/representantes/nuevo');
      }
    });

    $router->get('/nuevo', function (): void {
      App::render('paginas/representantes/nuevo', [], 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Nuevo representante']);
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
    });
  });

  $router->group('maestros', function (Router $router): void {
    $router->get('/', function (): void {
      $idAutenticado = App::view()->get('usuario')->id;

      $maestros = bd()->query("
        SELECT id, nombres, apellidos, cedula, fecha_nacimiento as fechaNacimiento,
        direccion, telefono, correo, rol, esta_activo as estaActivo,
        fecha_registro as fechaRegistro
        FROM usuarios WHERE rol = 'Docente' AND id != $idAutenticado
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

      App::render('paginas/periodos/listado', compact('periodos'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Períodos']);
    });

    $router->post('/', function (): void {
      $añoInicio = (int) App::request()->data['anio_inicio'];
      bd()->beginTransaction();

      try {
        bd()->query("INSERT INTO periodos (anio_inicio) VALUES ($añoInicio)");
        $idDelPeriodo = bd()->lastInsertId();
        bd()->query("
          INSERT INTO momentos (numero_momento, mes_inicio, dia_inicio, id_periodo)
          VALUES (1, 1, 1, $idDelPeriodo), (2, 5, 1, $idDelPeriodo),
          (3, 9, 1, $idDelPeriodo)
        ");

        bd()->commit();
        $_SESSION['mensajes.exito'] = "Período $añoInicio aperturado exitósamente";
        App::redirect('/periodos');
      } catch (PDOException $error) {
        bd()->rollBack();

        if (str_contains($error, 'periodos.anio_inicio')) {
          $_SESSION['mensajes.error'] = "Periodo $añoInicio ya fue aperturado";
        } else {
          throw $error;
        }

        App::redirect('/periodos/nuevo');
      }
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

    // $router->get('/@periodo:[0-9]{4}', function (int $periodo): void {
    //   $periodo = bd()
    //     ->query("
    //       SELECT p.id, p.anio_inicio as inicio, p.fecha_registro as fechaRegistro
    //       FROM periodos p
    //       JOIN momentos m
    //       ON p.id = m.id_periodo
    //       WHERE inicio = $periodo
    //     ")->fetchAll();

    //   dd($periodo);
    //   exit;

    //   App::render('paginas/periodos/editar', compact('periodo'), 'pagina');
    //   App::render('plantillas/privada', ['titulo' => 'Editar momentos']);
    // });
  });

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
      ");

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
      // TODO: no está actualizando clave
      $claves = App::request()->data->getData();
      $usuario = App::view()->get('usuario');
      $nuevaClave = Usuario::encriptar($claves['nueva_clave']);

      assert($usuario instanceof Usuario);

      if ($usuario->validarClave($claves['antigua_clave'])) {
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
        INSERT INTO salas (nombre, edad_minima, edad_maxima)
        VALUES (:nombre, :edadMinima, :edadMaxima)
      ');

      $sentencia->bindValue(':nombre', $sala['nombre']);
      $sentencia->bindValue(':edadMinima', $sala['edad_minima'], PDO::PARAM_INT);
      $sentencia->bindValue(':edadMaxima', $sala['edad_maxima'], PDO::PARAM_INT);

      try {
        $sentencia->execute();
        $_SESSION['mensajes.exito'] = 'Sala aperturada exitósamente';
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
        FROM usuarios WHERE rol = 'Docente' AND id != $idAutenticado
      ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);

      $salas = bd()->query("
        SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
        esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
      ")->fetchAll(PDO::FETCH_CLASS, Sala::class);

      App::render(
        'paginas/salas/asignar',
        compact('periodos', 'periodoActual', 'maestros', 'salas'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => 'Asignar maestro a sala']);
    });

    $router->post('/asignar', function (): void {
      $asignacion = App::request()->data->getData();

      $sentencia = bd()->prepare("
        INSERT INTO asignaciones_de_docentes (id_docente, id_sala, id_periodo)
        VALUES (:idDocente, :idSala, :idPeriodo)
      ");

      $sentencia->bindValue(':idDocente', $asignacion['id_maestro'], PDO::PARAM_INT);
      $sentencia->bindValue(':idSala', $asignacion['id_sala'], PDO::PARAM_INT);
      $sentencia->bindValue(':idPeriodo', $asignacion['id_periodo'], PDO::PARAM_INT);
      $sentencia->execute();

      $_SESSION['mensajes.exito'] = 'Maestro asignado exitósamente';
      App::redirect('/');
    });

    $router->group('/@id:[0-9]{1,}', function (Router $router): void {
      $router->get('/', function (int $id): void {
        $sala = bd()->query("
          SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
          esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
          WHERE id = $id
        ")->fetchObject(Sala::class);

        App::render('paginas/salas/editar', compact('sala'), 'pagina');
        App::render('plantillas/privada', ['titulo' => 'Editar sala']);
      });

      $router->post('/', function (int $id): void {
        $sala = App::request()->data->getData();

        $sentencia = bd()->prepare("
          UPDATE salas SET nombre = :nombre, edad_minima = :edadMinima,
          edad_maxima = :edadMaxima WHERE id = $id
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

      $router->get('/habilitar', function (int $id): void {
        bd()->query("UPDATE salas SET esta_activa = TRUE WHERE id = $id");
        $_SESSION['mensajes.exito'] = 'Sala habilitada exitósamente';
        App::redirect('/salas');
      });

      $router->get('/inhabilitar', function (int $id): void {
        bd()->query("UPDATE salas SET esta_activa = FALSE WHERE id = $id");
        $_SESSION['mensajes.exito'] = 'Sala inhabilitada exitósamente';
        App::redirect('/salas');
      });
    });
  });

  $router->group('momentos', function (Router $router): void {
    $router->get('/', function (): void {
      $momentos = bd()->query("
        SELECT m.id, numero_momento as numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio, m.fecha_registro as fechaRegistro,
        anio_inicio as periodo FROM momentos m JOIN periodos p
        ON m.id_periodo = p.id ORDER BY m.id
      ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

      $mesActual = (int) date('m');

      $ultimoMomento = bd()->query("
        SELECT id, numero_momento as numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio, fecha_registro as fechaRegistro,
        id_periodo as idPeriodo FROM momentos
        WHERE mesInicio >= $mesActual
        ORDER BY idPeriodo, mesInicio
        LIMIT 1
      ")->fetchObject(Momento::class);

      App::render(
        'paginas/momentos/listado',
        compact('momentos', 'ultimoMomento'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => 'Momentos']);
    });
  });

  $router->group('estudiantes', function (Router $router): void {
    $router->get('/', function (): void {
      $estudiantes = bd()->query("
        SELECT id, nombres, apellidos, cedula_escolar as cedula,
        fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
        genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
        id_mama as idMama, id_papa as idPapa FROM estudiantes
      ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

      App::render('paginas/estudiantes/listado', compact('estudiantes'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Estudiantes']);
    });

    $router->get('/inscribir', function (): void {
      $estudiantes = bd()->query("
        SELECT id, nombres, apellidos, cedula_escolar as cedula,
        fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
        genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
        id_mama as idMama, id_papa as idPapa FROM estudiantes
      ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

      $momentos = bd()->query("
        SELECT m.id, numero_momento as numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio, m.fecha_registro as fechaRegistro,
        anio_inicio as periodo FROM momentos m JOIN periodos p
        ON m.id_periodo = p.id ORDER BY m.id
      ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

      $mesActual = (int) date('m');

      $ultimoMomento = bd()->query("
        SELECT id, numero_momento as numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio, fecha_registro as fechaRegistro,
        id_periodo as idPeriodo FROM momentos
        WHERE mesInicio >= $mesActual
        ORDER BY idPeriodo, mesInicio
        LIMIT 1
      ")->fetchObject(Momento::class);

      App::render(
        'paginas/estudiantes/inscribir',
        compact('estudiantes', 'momentos', 'ultimoMomento'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => 'Inscribir estudiante']);
    });

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

        $consulta = "SELECT a.id_docente FROM asignaciones_de_docentes a
        JOIN usuarios d
        ON a.id_docente = d.id
        WHERE a.id = {$inscripcion['id_asignacion_docente']}";

        dd($consulta, $inscripcion);

        $idDelDocente = (int) bd()->query($consulta)->fetchColumn();

        $idDelAsistente = (int) bd()->query("
          SELECT u.id as idDelDocente FROM asignaciones_de_docentes a
          JOIN usuarios u ON a.id_docente = u.id
          WHERE a.id = {$inscripcion['id_asignacion_asistente']}
        ")->fetchColumn();

        $sentencia = bd()->prepare("SELECT id_periodo FROM momentos WHERE id = ?");
        $sentencia->execute([$inscripcion['id_momento']]);
        $idPeriodo = $sentencia->fetchColumn();

        $idsMomentos = bd()
          ->query("SELECT id FROM momentos WHERE id_periodo = $idPeriodo")
          ->fetchAll();

        $idsMomentos = array_column($idsMomentos, 'id');

        $sentenciaBoletines = bd()->prepare("
          INSERT INTO boletines (numero_inasistencias, nombre_proyecto,
          descripcion_formacion, descripcion_ambiente, recomendaciones,
          id_estudiante, id_momento, id_docente, id_asistente) VALUES (
          0, 'No establecido', 'No establecida', 'No establecida',
          'No establecidas', :idEstudiante, :idMomento, :idDocente, :idAsistente)
        ");

        $sentenciaBusquedaBoletin = bd()->prepare("
          SELECT COUNT(id) FROM boletines WHERE id_estudiante = ?
          AND id_momento = ?
        ");

        foreach ($idsMomentos as $idMomento) {
          $sentenciaBusquedaBoletin->execute([$idDelEstudiante, $idMomento]);
          $noAñadirBoletin = $sentenciaBusquedaBoletin->fetchColumn();

          if ($noAñadirBoletin) {
            continue;
          }

          $sentenciaBoletines->bindValue(':idEstudiante', $idDelEstudiante, PDO::PARAM_INT);
          $sentenciaBoletines->bindValue(':idMomento', $idMomento, PDO::PARAM_INT);
          $sentenciaBoletines->bindValue(':idDocente', $idDelDocente, PDO::PARAM_INT);
          $sentenciaBoletines->bindValue(':idAsistente', $idDelAsistente, PDO::PARAM_INT);

          dd($sentenciaBoletines->debugDumpParams());
          // $sentenciaBoletines->execute();
        }

        $sentencia = bd()->query("
          INSERT INTO boletines (numero_inasistencias, nombre_proyecto,
          descripcion_formacion, descripcion_ambiente, recomendaciones,
          id_estudiante, id_momento, id_docente, id_asistente) VALUES (
          0, 'No establecido', 'No establecida', 'No establecida',
          'No establecidas', $idDelEstudiante, {$inscripcion['id_momento']},
          $idDelDocente, $idDelAsistente)
        ");

        bd()->commit();
        $_SESSION['mensajes.exito'] = 'Estudiante inscrito exitósamente';
        App::redirect('/inscripciones');
      } catch (PDOException $error) {
        bd()->rollBack();

        throw $error;
        App::redirect('/estudiantes/inscribir');
      }
    });

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
    });

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
    });
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
}]);
