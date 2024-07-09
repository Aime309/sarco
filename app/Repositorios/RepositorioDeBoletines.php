<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Usuario;

final readonly class RepositorioDeBoletines {
  function __construct(
    private PDO $pdo,
    private RepositorioDeUsuarios $repositorioDeUsuarios,
    private RepositorioDeEstudiantes $repositorioDeEstudiantes,
    private RepositorioDeMomentos $repositorioDeMomentos,
    private RepositorioDePeriodos $repositorioDePeriodos
  ) {
  }

  /** @return Boletin[] */
  function todos(): array {
    $sentencia = $this->pdo->query('
      SELECT b.id, b.fecha_registro as fechaRegistro,
      numero_inasistencias as inasistencias,
      nombre_proyecto as proyecto,
      descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente,
      recomendaciones, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante,
      id_momento, id_asignacion_sala, id_estudiante
      FROM boletines b
      JOIN momentos m
      JOIN estudiantes e
      ON b.id_momento = m.id AND b.id_estudiante = e.id
    ');

    $boletines = [];

    while ($boletin = $sentencia->fetchObject(Boletin::class)) {
      $boletines[] = $this->completarInfo($boletin);
    }

    return $boletines;
  }

  function buscar(string $id): ?Boletin {
    $sentencia = $this->pdo->prepare('
      SELECT b.id, b.fecha_registro as fechaRegistro,
      numero_inasistencias as inasistencias,
      nombre_proyecto as proyecto,
      descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente,
      recomendaciones, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante,
      e.cedula as cedulaEstudiante,
      id_momento, id_asignacion_sala, id_estudiante
      FROM boletines b JOIN estudiantes e
      ON b.id_estudiante = e.id
      WHERE b.id = ?
    ');

    $sentencia->execute([$id]);
    $boletin = $sentencia->fetchObject(Boletin::class) ?: null;

    if ($boletin) {
      $this->completarInfo($boletin);
    }

    return $boletin;
  }

  function todosDelMismoPeriodoPorId(string $id): array {
    $sentencia = $this->pdo->prepare('
      SELECT m.id_periodo, b.id_estudiante FROM boletines b
      JOIN momentos m ON b.id_momento = m.id
      WHERE b.id = ?
    ');

    $sentencia->execute([$id]);
    [$idPeriodo, $idEstudiante] = $sentencia->fetch(PDO::FETCH_NUM);
    $periodo = $this->repositorioDePeriodos->buscarPorId($idPeriodo);

    $idsMomentos = array_map(
      callback: fn (Momento $momento) => $momento->id,
      array: $periodo->momentos()
    );

    $sentencia = $this->pdo->prepare('
      SELECT b.id, b.fecha_registro as fechaRegistro,
      numero_inasistencias as inasistencias,
      nombre_proyecto as proyecto,
      descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente,
      recomendaciones, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante,
      e.cedula as cedulaEstudiante,
      id_momento, id_asignacion_sala, id_estudiante
      FROM boletines b JOIN estudiantes e
      JOIN momentos m
      ON b.id_estudiante = e.id AND b.id_momento = m.id
      WHERE b.id_estudiante = :idEstudiante
      AND b.id_momento IN (:idMomento1, :idMomento2, :idMomento3)
      ORDER BY m.numero
    ');

    $sentencia->execute([
      ':idEstudiante' => $idEstudiante,
      ':idMomento1' => $idsMomentos[0],
      ':idMomento2' => $idsMomentos[1],
      ':idMomento3' => $idsMomentos[2]
    ]);

    $boletines = [];

    while ($boletin = $sentencia->fetchObject(Boletin::class)) {
      $boletines[] = $this->completarInfo($boletin);
    }

    return $boletines;
  }

  private function completarInfo(Boletin $boletin): Boletin {
    $asignaciones = $this->pdo->query("
      SELECT id_docente1, id_docente2, id_docente3 FROM asignaciones_de_salas
      WHERE id = '{$boletin->id_asignacion_sala}'
    ")->fetchAll(PDO::FETCH_ASSOC);

    $ids = [];

    foreach ($asignaciones as $asignacion) {
      $ids[] = $asignacion['id_docente1'];
      $ids[] = $asignacion['id_docente2'];

      if ($asignacion['id_docente3']) {
        $ids[] = $asignacion['id_docente3'];
      }
    }

    $docentes = array_map(function (string $id): Usuario {
      return $this->repositorioDeUsuarios->buscarPorId($id);
    }, $ids);

    $estudiante = $this
      ->repositorioDeEstudiantes
      ->buscarPorId($boletin->id_estudiante);

    $momento = $this
      ->repositorioDeMomentos
      ->buscar($boletin->id_momento);

    $boletin
      ->asignarDocentes(...$docentes)
      ->asignarEstudiante($estudiante)
      ->asignarMomento($momento);

    return $boletin;
  }
}
