<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Inscripcion;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Representante;
use SARCO\Modelos\Usuario;
use Symfony\Component\Uid\UuidV4;

require_once __DIR__ . '/intermediarios.php';

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

  $router->get(
    '/asignaciones/@idPeriodo/@idSala',
    function (string $idPeriodo, string $idSala): void {
      $sentencia = bd()->prepare('
        SELECT a.id as idAsignacion, au.codigo, au.tipo, d.nombres, d.apellidos
        FROM asignaciones_de_salas a
        JOIN aulas au
        JOIN usuarios d
        ON (
          a.id_docente1 = d.id
          OR a.id_docente2 = d.id
          OR a.id_docente3 = d.id
        ) AND a.id_aula = au.id
        WHERE a.id_periodo = :idPeriodo
        AND a.id_sala = :idSala
      ');

      $sentencia->execute([
        ':idPeriodo' => $idPeriodo,
        ':idSala' => $idSala
      ]);

      $asignaciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

      $aula = [];
      $docentes = [];

      foreach ($asignaciones as $asignacion) {
        $aula = [
          'codigo' => $asignacion['codigo'],
          'tipo' => $asignacion['tipo']
        ];

        $docentes[] = [
          'nombres' => $asignacion['nombres'],
          'apellidos' => $asignacion['apellidos']
        ];
      }

      $inscripciones = 0;
      $inscripcionesExcedidas = false;

      if (count($asignaciones) > 0) {
        $sentencia = bd()->prepare("
          SELECT COUNT(id) FROM inscripciones
          WHERE id_periodo = :idPeriodo AND id_asignacion_sala = :idAsignacion
        ");

        $sentencia->execute([
          ':idPeriodo' => $idPeriodo,
          ':idAsignacion' => $asignaciones[0]['idAsignacion']
        ]);

        $inscripciones = $sentencia->fetchColumn();

        if ($aula['tipo'] === 'Pequeña') {
          if ($inscripciones > 29) {
            $inscripcionesExcedidas = true;
          }
        } elseif ($aula['tipo'] === 'Grande') {
          if ($inscripciones > 32) {
            $inscripcionesExcedidas = true;
          }
        }
      }

      $idAsignacion = $asignaciones[0]['idAsignacion'] ?? null;

      App::json(compact(
        'aula',
        'docentes',
        'inscripciones',
        'inscripcionesExcedidas',
        'idAsignacion'
      ));
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
}, [permitirSiNoHayDirectoresActivos()]);

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

  $router->group(
    'periodos',
    require __DIR__ . '/rutas/periodos.php',
    [autorizar(Rol::Director)]
  );

  $router->group('perfil', require __DIR__ . '/rutas/perfil.php');

  $router->group('salas', require __DIR__ . '/rutas/salas.php', [
    autorizar(Rol::Director, Rol::Secretario)
  ]);

  $router->group('aulas', require __DIR__ . '/rutas/aulas.php', [
    autorizar(Rol::Director, Rol::Secretario)
  ]);

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
          INSERT INTO representantes (id, nombres, apellidos, cedula, genero,
          fecha_nacimiento, estado_civil, nacionalidad, telefono, correo)
          VALUES (:id, :nombres, :apellidos, :cedula, :genero, :fechaNacimiento,
          :estadoCivil, :nacionalidad, :telefono, :correo)
        ");

        if (!empty($inscripcion['padre']['nombres'])) {
          $idDelPapa = new UuidV4;
          $sentencia->bindValue(':id', $idDelPapa);
          $sentencia->bindValue(':nombres', $inscripcion['padre']['nombres']);
          $sentencia->bindValue(':apellidos', $inscripcion['padre']['apellidos']);
          $sentencia->bindValue(':cedula', $inscripcion['padre']['cedula'], PDO::PARAM_INT);
          $sentencia->bindValue(':genero', Genero::Masculino->value);
          $sentencia->bindValue(':fechaNacimiento', $inscripcion['padre']['fecha_nacimiento']);
          $sentencia->bindValue(':estadoCivil', $inscripcion['padre']['estado_civil']);
          $sentencia->bindValue(':nacionalidad', $inscripcion['padre']['nacionalidad']);
          $sentencia->bindValue(':telefono', $inscripcion['padre']['telefono']);
          $sentencia->bindValue(':correo', $inscripcion['padre']['correo']);

          $sentencia->execute();
        }

        $idDeLaMama = new UuidV4;
        $sentencia->bindValue(':id', $idDeLaMama);
        $sentencia->bindValue(':nombres', $inscripcion['madre']['nombres']);
        $sentencia->bindValue(':apellidos', $inscripcion['madre']['apellidos']);
        $sentencia->bindValue(':cedula', $inscripcion['madre']['cedula'], PDO::PARAM_INT);
        $sentencia->bindValue(':genero', Genero::Femenino->value);
        $sentencia->bindValue(':fechaNacimiento', $inscripcion['madre']['fecha_nacimiento']);
        $sentencia->bindValue(':estadoCivil', $inscripcion['madre']['estado_civil']);
        $sentencia->bindValue(':nacionalidad', $inscripcion['madre']['nacionalidad']);
        $sentencia->bindValue(':telefono', $inscripcion['madre']['telefono']);
        $sentencia->bindValue(':correo', $inscripcion['madre']['correo']);

        $sentencia->execute();
        $idDelPapa ??= 'NULL';
        [$añoDeNacimiento] = explode('-', $inscripcion['estudiante']['fecha_nacimiento']);
        $ultimosDigitosAñoNacimiento = substr($añoDeNacimiento, 2);
        $cedulaEscolar = "v-1{$ultimosDigitosAñoNacimiento}{$inscripcion['madre']['cedula']}";

        $sentencia = bd()->prepare("
          INSERT INTO estudiantes (id, nombres, apellidos, cedula,
          fecha_nacimiento, lugar_nacimiento, genero, tipo_sangre, id_mama,
          id_papa) VALUES (:id, :nombres, :apellidos, :cedula, :fechaNacimiento,
          :lugarNacimiento, :genero, :grupoSanguineo, '$idDeLaMama', '$idDelPapa')
        ");

        $idDelEstudiante = new UuidV4;
        $sentencia->bindValue(':id', $idDelEstudiante);
        $sentencia->bindValue(':nombres', $inscripcion['estudiante']['nombres']);
        $sentencia->bindValue(':apellidos', $inscripcion['estudiante']['apellidos']);
        $sentencia->bindValue(':cedula', $cedulaEscolar);
        $sentencia->bindValue(':fechaNacimiento', $inscripcion['estudiante']['fecha_nacimiento']);
        $sentencia->bindValue(':lugarNacimiento', $inscripcion['estudiante']['lugar_nacimiento']);
        $sentencia->bindValue(':genero', $inscripcion['estudiante']['genero']);
        $sentencia->bindValue(':grupoSanguineo', $inscripcion['estudiante']['grupo_sanguineo']);
        $sentencia->execute();

        $sentencia = bd()->prepare("
          INSERT INTO inscripciones (id, id_periodo, id_estudiante,
          id_asignacion_sala) VALUES (:id, :idPeriodo, :idEstudiante, :idAsignacion)
        ");

        $sentencia->bindValue(':id', new UuidV4);
        $sentencia->bindValue(':idPeriodo', $inscripcion['id_periodo']);
        $sentencia->bindValue(':idEstudiante', $idDelEstudiante);
        $sentencia->bindValue(':idAsignacion', $inscripcion['id_asignacion_sala']);
        $sentencia->execute();

        $momentos = bd()->query("
          SELECT m.id, numero, mes_inicio as mesInicio,
          dia_inicio as diaInicio,
          mes_cierre as mesCierre,
          dia_cierre as diaCierre,
          m.fecha_registro as fechaRegistro
          FROM momentos m
          JOIN periodos p
          ON id_periodo = p.id
          WHERE id_periodo = '{$inscripcion['id_periodo']}'
          ORDER BY numero
        ")->fetchAll(PDO::FETCH_CLASS, Momento::class);

        $sentencia = bd()->prepare("
          INSERT INTO boletines (id, numero_inasistencias, nombre_proyecto,
          descripcion_formacion, descripcion_ambiente, recomendaciones,
          id_estudiante, id_momento, id_asignacion_sala) VALUES (:id, 0,
          'No especificado', 'No especificada', 'No especificada',
          'No especificadas', :idEstudiante, :idMomento, :idAsignacion)
        ");

        foreach ($momentos as $momento) {
          $sentencia->execute([
            ':id' => new UuidV4,
            ':idEstudiante' => $idDelEstudiante,
            ':idMomento' => $momento->id,
            ':idAsignacion' => $inscripcion['id_asignacion_sala']
          ]);
        }

        bd()->commit();
        $_SESSION['mensajes.exito'] = 'Estudiante inscrito exitósamente';
        App::redirect('/inscripciones');
      } catch (PDOException $error) {
        bd()->rollBack();

        dd($error, $inscripcion);
        throw $error;
        App::redirect('/estudiantes/inscribir');
      }
    })->addMiddleware(autorizar(Rol::Secretario));

    $router->get('/boletines', function (): void {
      $boletines = bd()->query("
        SELECT b.id, numero_inasistencias as inasistencias,
        nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
        descripcion_ambiente as descripcionAmbiente, recomendaciones,
        b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
        e.apellidos as apellidosEstudiante, e.cedula as cedulaEstudiante,
        m.numero as momento, b.id_asignacion_sala as idAsignacion
        FROM boletines b
        JOIN estudiantes e
        JOIN momentos m
        ON b.id_estudiante = e.id
        AND b.id_momento = m.id
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
        p.anio_inicio as periodo, e.nombres as nombresEstudiante,
        e.apellidos as apellidosEstudiante
        FROM inscripciones i JOIN periodos p JOIN estudiantes e
        JOIN asignaciones_de_salas a ON i.id_periodo = p.id AND i.id_estudiante = e.id
        AND i.id_asignacion_sala = a.id
      ")->fetchAll(PDO::FETCH_CLASS, Inscripcion::class);

      App::render(
        'paginas/inscripciones/listado',
        compact('inscripciones'),
        'pagina'
      );

      App::render('plantillas/privada', ['titulo' => 'Inscripciones']);
    });
  });
}, [
  mostrarFormularioDeIngresoSiNoEstaAutenticado(),
  permitirUsuariosActivos(),
  notificarSiLimiteDePeriodoExcedido()
]);
