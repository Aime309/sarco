<?php

namespace SARCO\Enumeraciones;

enum EstadoCivil: string {
  case Casado = 'Casado';
  case Soltero = 'Soltero';
  case Divorciado = 'Divorciado';
  case Viudo = 'Viudo';

  function obtenerPorGenero(Genero $genero): string {
    if ($genero === Genero::Masculino) {
      return $this->name;
    }

    return match ($this) {
      self::Casado => 'Casada',
      self::Soltero => 'Soltera',
      self::Divorciado => 'Divorciada',
      self::Viudo => 'Viuda'
    };
  }
}
