<?php

namespace SARCO\Modelos;

use DateTime;

final readonly class Usuario {
  public int $id;
  public string $nombres;
  public string $apellidos;
  public int $cedula;
  public string $fechaNacimiento;
  public string $direccion;
  public string $telefono;
  public string $correo;
  public string $rol;
  public bool $estaActivo;
  public string $fechaRegistro;

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
