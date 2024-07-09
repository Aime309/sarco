<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Momento;

final readonly class RepositorioDeMomentos {
  function __construct(
    private PDO $pdo,
    private RepositorioDePeriodos $repositorioDePeriodos
  ) {
  }

  function buscar(string $id): ?Momento {
    $sentencia = $this->pdo->prepare('
      SELECT id, fecha_registro as fechaRegistro, numero, mes_inicio as mesInicio,
      dia_inicio as diaInicio, mes_cierre as mesCierre, dia_cierre as diaCierre,
      id_periodo FROM momentos WHERE id = ?
    ');

    $sentencia->execute([$id]);
    $momento = $sentencia->fetchObject(Momento::class) ?: null;

    if ($momento instanceof Momento) {
      $periodo = $this
        ->repositorioDePeriodos
        ->buscarPorId($momento->id_periodo);

      $momento->asignarPeriodo($periodo);
    }

    return $momento;
  }
}
