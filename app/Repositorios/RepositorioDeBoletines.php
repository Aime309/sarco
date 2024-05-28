<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Boletin;

final readonly class RepositorioDeBoletines {
  function __construct(private PDO $pdo) {}

  /** @return Boletin[] */
  function todos(): array {
    $boletines = $this->pdo->query('
      SELECT id, fecha_registro as fechaRegistro,
      numero_inasistencias as inasistencias,
      nombre_proyecto as nombreProyecto,
      descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente,
      recomendaciones, id_estudiante, id_momento, id_asignacion_sala
    ')->fetchAll(PDO::FETCH_CLASS, Boletin::class);

    return $boletines;
  }
}
