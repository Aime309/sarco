<?php

namespace SARCO\Modelos;

use DateTimeImmutable;
use Jenssegers\Date\Date;
use Stringable;

final class Momento extends Modelo implements Stringable {
  public readonly int $numero;
  public readonly int $mesInicio;
  public readonly int $diaInicio;
  public readonly int $mesCierre;
  public readonly int $diaCierre;
  private ?Periodo $periodo = null;

  function cierre(string $formato): string {
    $fechaCompleta = "{$this->periodo->inicio}-$this->mesCierre-$this->diaCierre";
    $fechaCompleta = new DateTimeImmutable($fechaCompleta);

    return $fechaCompleta->format($formato);
  }

  function inicio(string $formato): string {
    $fechaCompleta = "{$this->periodo->inicio}-$this->mesInicio-$this->diaInicio";
    $fechaCompleta = new DateTimeImmutable($fechaCompleta);

    return $fechaCompleta->format($formato);
  }

  function fechaCompleta(): string {
    $inicio = Date::createFromFormat('m-d', "$this->mesInicio-$this->diaInicio")
      ->format('j \d\e F');
    $cierre = Date::createFromFormat('m-d', "$this->mesCierre-$this->diaCierre")
      ->format('j \d\e F');

    return "$inicio al $cierre";
  }

  function asignarPeriodo(Periodo $periodo): void {
    $this->periodo = $periodo;
  }

  function periodo(): ?Periodo {
    return $this->periodo;
  }

  function __toString(): string {
    return 'Momento ' . $this->numero;
  }
}
