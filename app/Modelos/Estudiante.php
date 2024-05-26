<?php

namespace SARCO\Modelos;

use DateTime;
use InvalidArgumentException;

final class Estudiante extends Modelo {
  public readonly string $nombres;
  public readonly string $apellidos;
  public readonly string $cedula;
  public readonly string $fechaNacimiento;
  public readonly string $lugarNacimiento;
  public readonly string $genero;
  public readonly string $grupoSanguineo;
  public readonly int $idMama;
  public readonly ?int $idPapa;

  function nombreCompleto(): string {
    return "$this->nombres $this->apellidos";
  }

  function edad(): int {
    return self::calcularEdad($this->fechaNacimiento);
  }

  static function calcularEdad(string $fechaNacimiento): int {
    $fechaNacimiento = new DateTime($fechaNacimiento);
    $fechaActual = time();
    $diferencia = $fechaActual - $fechaNacimiento->getTimestamp();

    return date('Y', $diferencia) - 1970;
  }

  /** @throws InvalidArgumentException */
  static function asegurarValidez(array $datos): void {
    $validacionNombresYApellidos = '/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{2,19}(\s?|\s?[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{2,19})$/';
    $edad = self::calcularEdad($datos['fecha_nacimiento'] ?? null);

    if (!preg_match($validacionNombresYApellidos,  $datos['nombres'] ?? '')) {
      throw new InvalidArgumentException('Los nombres sólo pueden contener letras con iniciales en mayúscula');
    } elseif (!preg_match($validacionNombresYApellidos, $datos['apellidos'] ?? '')) {
      throw new InvalidArgumentException('Los apellidos sólo pueden contener letras con iniciales en mayúscula');
    } elseif ($edad > 5) {
      throw new InvalidArgumentException('Debe tener máximo 5 años de edad');
    }
  }
}
