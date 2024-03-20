<?php

namespace SARCO\Modelos;

use DateTimeImmutable;

readonly class Representante {
  function __construct(
    public int $id,
    public int $cedula,
    private string $nombres,
    private string $apellidos,
    public Sexo $sexo,
    public DateTimeImmutable $fechaNacimiento,
    public string $telefono,
    public string $correo,
    public string $direccion,
    public DateTimeImmutable $fechaDeRegistro
  ) {}

  function nombreCompleto(): string {
    return "{$this->nombres} {$this->apellidos}";
  }

  function obtenerEdad(): int {
    $fechaNacimientoTimestamp = $this->fechaNacimiento->getTimestamp();
    $timestampActual = time();

    $diferencia = $timestampActual - $fechaNacimientoTimestamp;

    $edad = date('Y', $diferencia);
    $edad -= 1970;

    return abs($edad);
  }
}
