<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Estudiante;
use SARCO\Repositorios\RepositorioDeUsuarios;

App::post('/api/ingresar', function (): void {
  $credenciales = App::request()->data->getData();

  $usuarioEncontrado = App::get('contenedor')
    ->get(RepositorioDeUsuarios::class)
    ->buscar($credenciales['cedula']);

  if (!$usuarioEncontrado?->validarClave($credenciales['clave'])) {
    exit(App::halt(401, 'Cédula o contraseña incorrecta'));
  } elseif (!$usuarioEncontrado->estaActivo) {
    exit(App::halt(401, 'Este usuario se encuentra desactivado'));
  }

  $_SESSION['usuario.id'] = $usuarioEncontrado->id;
  App::redirect('/');
});

return function (Router $router): void {
  $router->get('/', function (): void {
    App::json(['mensaje' => 'API funcionando correctamente']);
  });

  $router->get('/estudiantes', function (): void {
    $cedula = $_GET['cedula'] ?? '';

    if ($cedula) {
      $estudiantes = bd()->query("
        SELECT id, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
        genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
        id_mama as idMama, id_papa as idPapa FROM estudiantes
        WHERE cedula LIKE '$cedula%'
      ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);
    } else {
      $estudiantes = bd()->query("
        SELECT id, nombres, apellidos, cedula,
        fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
        genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
        id_mama as idMama, id_papa as idPapa FROM estudiantes
      ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);
    }

    App::json($estudiantes);
  });

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
  )->addMiddleware(autorizar(Rol::Secretario));

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
  )->addMiddleware(autorizar(Rol::Secretario));
};
