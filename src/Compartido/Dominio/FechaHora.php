<?php

namespace SARCOV2\Compartido\Dominio;

use DateTimeImmutable;

final readonly class FechaHora extends Fecha {
  public int $horas;
  public int $minutos;
  public int $segundos;

  function __construct(
    int $año,
    int $mes,
    int $dia,
    int $horas,
    int $minutos,
    int $segundos
  ) {
    self::asegurarValidez($año, $mes, $dia, $horas, $minutos, $segundos);
    $this->inicializar($año, $mes, $dia, $horas, $minutos, $segundos);
  }

  static function instanciar(string $formato, string $fecha): static {
    $fecha = DateTimeImmutable::createFromFormat($formato, $fecha);

    return new static(
      $fecha->format('Y'),
      $fecha->format('m'),
      $fecha->format('d'),
      $fecha->format('H'),
      $fecha->format('i'),
      $fecha->format('s')
    );
  }

  static function actual(): self {
    $fechaHora = new DateTimeImmutable;

    return new self(
      $fechaHora->format('Y'),
      $fechaHora->format('m'),
      $fechaHora->format('d'),
      $fechaHora->format('H'),
      $fechaHora->format('i'),
      $fechaHora->format('s')
    );
  }

  protected function inicializar(
    int $año,
    int $mes,
    int $dia,
    ?int $horas = null,
    ?int $minutos = null,
    ?int $segundos = null
  ): void {
    parent::inicializar($año, $mes, $dia);

    $this->horas = $horas;
    $this->minutos = $minutos;
    $this->segundos = $segundos;
  }

  protected function asegurarValidez(
    int $año,
    int $mes,
    int $dia,
    ?int $horas = null,
    ?int $minutos = null,
    ?int $segundos = null
  ): void {
    parent::asegurarValidez($año, $mes, $dia);

    // TODO: validar $horas, $minutos y $segundos
  }
}
