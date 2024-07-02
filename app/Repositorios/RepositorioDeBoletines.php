<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Usuario;

final readonly class RepositorioDeBoletines {
  function __construct(
    private PDO $pdo,
    private RepositorioDeUsuarios $repositorioDeUsuarios
  ) {
  }

  /** @return Boletin[] */
  function todos(): array {
    $boletines = $this->pdo->query('
      SELECT id, fecha_registro as fechaRegistro,
      numero_inasistencias as inasistencias,
      nombre_proyecto as nombreProyecto,
      descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente,
      recomendaciones, id_estudiante, id_momento, id_asignacion_sala
      FROM boletines
    ')->fetchAll(PDO::FETCH_CLASS, Boletin::class);

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
      m.numero as momento, id_asignacion_sala
      FROM boletines b JOIN estudiantes e
      JOIN momentos m
      ON b.id_estudiante = e.id AND b.id_momento = m.id
      WHERE b.id = ?
    ');

    $sentencia->execute([$id]);
    $boletin = $sentencia->fetchObject(Boletin::class) ?: null;

    if ($boletin) {
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

      $boletin->asignarDocentes(...$docentes);
    }

    return $boletin;
  }
}
