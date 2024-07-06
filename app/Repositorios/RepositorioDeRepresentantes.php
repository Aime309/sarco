<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Representante;

final readonly class RepositorioDeRepresentantes {
  function __construct(private PDO $pdo) {}

  function buscarPorId(string $id): ?Representante {
    $sentencia = $this->pdo->prepare("
      SELECT id, fecha_registro as fechaRegistro, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, genero, telefono, correo,
      estado_civil as estadoCivil, nacionalidad FROM representantes
      WHERE id = ?
    ");

    $sentencia->execute([$id]);

    return $sentencia->fetchObject(Representante::class) ?: null;
  }
}
