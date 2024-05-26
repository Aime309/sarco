<?php

namespace SARCO\Modelos;

use InvalidArgumentException;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;

final class Usuario extends Persona {
  public string $direccion;
  public string $rol;
  public bool $estaActivo;
  public string $clave;

  function rol(): Rol {
    return match ($this->rol) {
      'Director', 'Directora' => Rol::Director,
      'Secretario', 'Secretaria' => Rol::Secretario,
      default => Rol::Docente
    };
  }

  function genero(): ?Genero {
    return match ($this->rol) {
      'Director', 'Secretario' => Genero::Masculino,
      'Docente' => null,
      default => Genero::Femenino
    };
  }

  function validarClave(string $clave): bool {
    return password_verify($clave, $this->clave);
  }

  function esDocente(): bool {
    return $this->rol() === Rol::Docente;
  }

  function esDirector(): bool {
    return $this->rol() === Rol::Director;
  }

  function esSecretario(): bool {
    return $this->rol() === Rol::Secretario;
  }

  static function encriptar(string $clave): string {
    return password_hash($clave, PASSWORD_DEFAULT);
  }

  static function asegurarValidez(array $datos): void {
    parent::asegurarValidez($datos);

    if (!preg_match('/^(?=.*\d)(?=.*[A-ZÑ])(?=.*\W).{8,}$/', $datos['clave'] ?? '')) {
      throw new InvalidArgumentException('La contraseña debe tener al menos 1 mayúscula, 1 número y un símbolo');
    } elseif (!Genero::tryFrom($datos['genero'] ?? '')) {
      throw new InvalidArgumentException('Género inválido, debe ser Masculino o Femenino');
    } elseif (mb_strlen($datos['direccion'] ?? '') < 3) {
      throw new InvalidArgumentException('La dirección debe tener al menos 3 caracteres');
    }
  }
}
