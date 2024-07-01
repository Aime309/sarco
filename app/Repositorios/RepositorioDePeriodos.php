<?php

namespace SARCO\Repositorios;

use PDO;
use PDOException;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;

final readonly class RepositorioDePeriodos {
  function __construct(private PDO $pdo) {}

  /** @return Periodo[] */
  function todos(): array {
    $periodos = $this->pdo->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC
    ")->fetchAll(PDO::FETCH_CLASS, Periodo::class);

    foreach ($periodos as $periodo) {
      $momentos = $this->pdo->query("
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

      $this->pdo->beginTransaction();

      try {
        $this->pdo->exec("DELETE FROM momentos WHERE id_periodo = '$periodo->id'");
        $this->pdo->exec("DELETE FROM periodos WHERE id = '$periodo->id'");
        $periodo->sePuedeEliminar = true;
      } catch (PDOException) {
        $periodo->sePuedeEliminar = false;
      }

      $this->pdo->rollBack();
      $periodo->asignarMomentos(...$momentos);
    }

    return $periodos;
  }
}
