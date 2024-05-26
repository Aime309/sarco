<?php

namespace SARCO\Modelos;

use InvalidArgumentException;
use Stringable;

final class Aula extends Modelo implements Stringable {
  public readonly string $codigo;
  public readonly string $tipo;

  function capacidad(): string {
    if (strtolower($this->tipo) === 'pequeña') {
      return '28-29';
    }

    return '31-32';
  }

  function esPequeña(): bool {
    return $this->tipo === 'Pequeña';
  }

  /** @throws InvalidArgumentException */
  final static function asegurarValidez(array $datos): void {
    if (empty($datos['tipo'])) {
      throw new InvalidArgumentException('El tipo de aula es requerido');
    } elseif (!preg_match(
      '/^(?=.*[0-9])(?=.*[A-ZÑa-zñ])(?=.*-).{3,}$/',
      $datos['codigo'] ?? ''
    )) {
      throw new InvalidArgumentException('El código de aula debe mínimo 3 letras, números y guiones');
    }
  }

  function __toString(): string {
    return $this->codigo;
  }
}
