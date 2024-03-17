<?php

namespace SARCO\Modelos;

use DateTime;
use DateTimeImmutable;

readonly class Usuario {
  function __construct(
    private string $nombre,
    private string $apellido,
    public int $cedula,
    public Rol $rol,
    public DateTimeImmutable $creado,
    public DateTime $actualizado
  ) {}

  function nombreCompleto(): string {
    return mb_convert_case("{$this->nombre} {$this->apellido}", MB_CASE_TITLE);
  }
}
