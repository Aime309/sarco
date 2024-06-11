<?php

namespace SARCO\Modelos;

use DateTime;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;

final class Estudiante extends Modelo {
  public readonly string $nombres;
  public readonly string $apellidos;
  public readonly string $cedula;
  public readonly string $fechaNacimiento;
  public readonly string $lugarNacimiento;
  public readonly string $genero;
  public readonly string $grupoSanguineo;
  public readonly string $idMama;
  public readonly ?string $idPapa;
  private array $representantes;

  function __construct() {
    $this->representantes = [];
  }

  function fechaNacimiento(string $formato = 'd/m/Y'): string {
    $fechaRegistro = DateTimeImmutable::createFromFormat(
      'Y-m-d H:i:s',
      $this->fechaRegistro
    );

    return $fechaRegistro->format($formato);
  }

  function asignarRepresentantes(?Representante ...$representantes): self {
    $this->representantes = $representantes;

    return $this;
  }

  function mama(): Representante {
    $mamas = array_filter(
      $this->representantes,
      fn (?Representante $representante) => $representante?->genero === 'Femenino'
    );

    $excepcion = new Exception('Mamá no ha sido asignada');

    return array_values($mamas)[0] ?? throw $excepcion;
  }

  function papa(): ?Representante {
    $papas =  array_filter(
      $this->representantes,
      fn (?Representante $representante) => $representante?->genero === 'Masculino'
    );

    return array_values($papas)[0] ?? null;
  }

  /** @return Representante[] */
  function representantes(): array {
    return array_filter(
      $this->representantes,
      fn (?Representante $representante) => $representante !== null
    );
  }

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
    $validacionNombresYApellidos = '/^(\s?[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{2,19}){2,3}$/';
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
