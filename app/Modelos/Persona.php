<?php

namespace SARCO\Modelos;

use DateTime;

abstract class Persona {
  public int $id;
  public string $nombres;
  public string $apellidos;
  public int $cedula;
  public string $fechaNacimiento;
  public string $telefono;
  public string $correo;
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
