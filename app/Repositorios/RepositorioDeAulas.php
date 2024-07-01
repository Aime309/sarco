<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Aula;

final readonly class RepositorioDeAulas {
  function __construct(private PDO $pdo) {
  }

  /** @return Aula[] */
  function todas(): array {
    $aulas = $this->pdo->query("
      SELECT id, fecha_registro as fechaRegistro, codigo, tipo
      FROM aulas ORDER BY tipo
    ")->fetchAll(PDO::FETCH_CLASS, Aula::class);

    return $aulas;
  }
}
