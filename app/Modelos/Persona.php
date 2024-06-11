<?php

namespace SARCO\Modelos;

use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;

abstract class Persona extends Modelo {
  public string $nombres;
  public string $apellidos;
  public int $cedula;
  public string $fechaNacimiento;
  public string $telefono;
  public string $correo;
  public string $genero;

  function nombreCompleto(): string {
    return "$this->nombres $this->apellidos";
  }

  final static function calcularEdad(?string $fechaNacimiento): int {
    $fechaNacimiento = new DateTimeImmutable($fechaNacimiento ?? '');
    $fechaActual = time();
    $diferencia = $fechaActual - $fechaNacimiento->getTimestamp();

    return date('Y', $diferencia) - 1970;
  }

  function edad(): int {
    $fechaNacimiento = new DateTime($this->fechaNacimiento);
    $fechaActual = time();
    $diferencia = $fechaActual - $fechaNacimiento->getTimestamp();

    return date('Y', $diferencia) - 1970;
  }

  /** @throws InvalidArgumentException */
  static function asegurarValidez(array $datos): void {
    $validacionNombresYApellidos = '/^(\s?[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{2,19}){2,3}$/';
    $cedula = $datos['cedula'] ?? -1;
    $edad = self::calcularEdad($datos['fecha_nacimiento'] ?? null);

    if (!preg_match($validacionNombresYApellidos,  $datos['nombres'] ?? '')) {
      throw new InvalidArgumentException('Los nombres sólo pueden contener letras con iniciales en mayúscula');
    } elseif (!preg_match($validacionNombresYApellidos, $datos['apellidos'] ?? '')) {
      throw new InvalidArgumentException('Los apellidos sólo pueden contener letras con iniciales en mayúscula');
    } elseif ($cedula < 1_000_000 || $cedula > 31_000_000) {
      throw new InvalidArgumentException('La cédula debe estar entre 1.000.000 y 31.000.000');
    } elseif (($datos['fecha_nacimiento'] ?? '') < '1906-01-01') {
      throw new InvalidArgumentException('La fecha de nacimiento debe ser mayor al 1/1/1906');
    } elseif ($edad < 18) {
      throw new InvalidArgumentException('Debe ser mayor de edad');
    } elseif (!preg_match('/^\+\d{2} \d{3}-\d{7}$/', $datos['telefono'] ?? '')) {
      throw new InvalidArgumentException('El teléfono no es válido, debe tener el formato +XX XXX-XXXXXXX');
    } elseif (!filter_var($datos['correo'] ?? '', FILTER_VALIDATE_EMAIL)) {
      throw new InvalidArgumentException('El correo es inválido');
    }
  }
}
