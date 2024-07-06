<?php

namespace SARCO\Repositorios;

use PDO;
use SARCO\Modelos\Estudiante;

final readonly class RepositorioDeEstudiantes {
  function __construct(
    private PDO $pdo,
    private RepositorioDeRepresentantes $repositorioDeRepresentantes
  ) {
  }

  function buscarPorId(string $id): ?Estudiante {
    $resultado = $this->pdo->prepare("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
      genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
      id_mama as idMama, id_papa as idPapa FROM estudiantes
      WHERE id = ?
    ");

    $resultado->execute([$id]);
    $estudiante = $resultado->fetchObject(Estudiante::class) ?: null;

    if ($estudiante) {
      $representantes = [
        $this
          ->repositorioDeRepresentantes
          ->buscarPorId($estudiante->idMama)
      ];

      if ($estudiante->idPapa) {
        $representantes[] = $this
          ->repositorioDeRepresentantes
          ->buscarPorId($estudiante->idPapa);
      }

      $estudiante->asignarRepresentantes(...$representantes);
    }

    return $estudiante;
  }
}
