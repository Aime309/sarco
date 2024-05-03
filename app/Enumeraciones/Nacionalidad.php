<?php

namespace SARCO\Enumeraciones;

enum Nacionalidad: string {
  case Venezolano = 'Venezolano';
  case Extranjero = 'Extranjero';

  function obtenerPorGenero(Genero $genero): string {
    if ($genero === Genero::Masculino) {
      return $this->name;
    }

    return match ($this) {
      self::Extranjero => 'Extranjera',
      self::Venezolano => 'Venezolana'
    };
  }
}
