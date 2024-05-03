<?php

namespace SARCO\Enumeraciones;

enum Rol: string {
  case Director = 'Director';
  case Secretario = 'Secretario';
  case Docente = 'Docente';

  function obtenerPorGenero(Genero $genero): string {
    if ($genero === Genero::Masculino || $this === self::Docente) {
      return $this->name;
    }

    return match ($this) {
      self::Director => 'Directora',
      self::Secretario => 'Secretaria'
    };
  }

  static function menoresQue(string $rol): array {
    return match ($rol) {
      'Director' => [self::Secretario, self::Docente],
      'Secretario' => [self::Docente],
      default => []
    };
  }
}
