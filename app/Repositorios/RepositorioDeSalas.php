<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Sala;

final readonly class RepositorioDeSalas {
  function __construct(private PDO $pdo) {}

  /** @return Sala[] */
  function todas(): array {
    $salas = $this->pdo->query("
      SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
      esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
    ")->fetchAll(PDO::FETCH_CLASS, Sala::class);

    return $salas;
  }

  function buscar(string $id): ?Sala {
    $sentencia = $this->pdo->prepare("
      SELECT id, nombre, edad_minima as edadMinima, edad_maxima as edadMaxima,
      esta_activa as estaActiva, fecha_registro as fechaRegistro FROM salas
      WHERE id = ?
    ");

    $sentencia->execute([$id]);

    return $sentencia->fetchObject(Sala::class) ?: null;
  }
}
