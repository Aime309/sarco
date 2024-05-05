<?php

namespace SARCO\Modelos;

use DateTime;

final class Estudiante extends Modelo {
  public readonly string $nombres;
  public readonly string $apellidos;
  public readonly string $cedula;
  public readonly string $fechaNacimiento;
  public readonly string $lugarNacimiento;
  public readonly string $genero;
  public readonly string $grupoSanguineo;
  public readonly int $idMama;
  public readonly ?int $idPapa;

  function nombreCompleto(): string {
    return "$this->nombres $this->apellidos";
  }

  function edad(): int {
    $fechaNacimiento = new DateTime($this->fechaNacimiento);
    $fechaActual = time();
    $diferencia = $fechaActual - $fechaNacimiento->getTimestamp();

    return date('Y', $diferencia) - 1970;
  }
}
