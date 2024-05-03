<?php

namespace SARCO\Enumeraciones;

enum Rol {
  case Director;
  case Secretario;
  case Docente;

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

  static function obtenerPorNombre(string $nombre): ?self {
    $roles = array_filter(
      self::cases(),
      static fn (self $rol): bool => $rol->name === $nombre
    );

    return @$roles[1];
  }
}
