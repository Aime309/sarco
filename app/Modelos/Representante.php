<?php

namespace SARCO\Modelos;

use InvalidArgumentException;
use SARCO\Enumeraciones\EstadoCivil;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Nacionalidad;

final class Representante extends Persona {
  public string $estadoCivil;
  public string $nacionalidad;

  function genero(): Genero {
    return match ($this->nacionalidad) {
      'Venezolana', 'Extranjera' => Genero::Femenino,
      default => Genero::Masculino
    };
  }

  function estadoCivil(): EstadoCivil {
    return match ($this->estadoCivil) {
      'Casada', 'Casado' => EstadoCivil::Casado,
      'Soltero', 'Soltera' => EstadoCivil::Soltero,
      'Divorciado', 'Divorciada' => EstadoCivil::Divorciado,
      default => EstadoCivil::Viudo
    };
  }

  function nacionalidad(): Nacionalidad {
    return match ($this->nacionalidad) {
      'Venezolana', 'Venezolano' => Nacionalidad::Venezolano,
      default => Nacionalidad::Extranjero
    };
  }

  static function asegurarValidez(array $datos): void {
    parent::asegurarValidez($datos);

    if (!EstadoCivil::tryFrom($datos['estado_civil'] ?? '')) {
      throw new InvalidArgumentException('El estado civil debe ser Casado, Soltero, Divorciado o Viudo');
    } elseif (!Nacionalidad::tryFrom($datos['nacionalidad'] ?? '')) {
      throw new InvalidArgumentException('La nacionalidad debe ser Venezolano o Extranjero');
    }
  }
}
