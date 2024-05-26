<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Modelos\Estudiante;

return function (Router $router): void {
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
};
