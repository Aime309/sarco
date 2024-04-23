<?php

namespace SARCOV2\Usuarios\Dominio;

use SARCOV2\Compartido\Dominio\Genero;

enum Rol: string {
  case Director = 'Director/a';
  case Secretario = 'Secretario/a';
  case Docente = 'Docente';

  function valor(Genero $genero): string {
    if ($genero === Genero::Masculino || $this === self::Docente) {
      return $this->name;
    }

    return $this->name . 'a';
  }
}
