<?php

namespace SARCOV2\Usuarios\Dominio;

use SARCOV2\Compartido\Dominio\{
  Apellidos,
  Cedula,
  Correo,
  Direccion,
  FechaHora,
  FechaNacimiento,
  Genero,
  ID,
  Nombres,
  Telefono
};
use SARCOV2\Compartido\Dominio\Excepciones\{
  ApellidosInvalidos,
  CedulaInvalida,
  CorreoInvalido,
  DireccionInvalida,
  FechaInvalida,
  NombresInvalidos,
  TelefonoInvalido
};
use SARCOV2\Usuarios\Dominio\Excepciones\{ClaveInvalida, UsuarioInvalido};

final class Usuario {
  function __construct(
    private readonly ID $id,
    private Nombres $nombres,
    private Apellidos $apellidos,
    private Cedula $cedula,
    private FechaNacimiento $fechaNacimiento,
    private Genero $genero,
    private Direccion $direccion,
    private Telefono $telefono,
    private Correo $correo,
    private Apodo $apodo,
    private Clave $clave,
    private Rol $rol,
    private FechaHora $fechaRegistro,
    private bool $estaActivo = true
  ) {
  }

  /**
   * @throws NombresInvalidos
   * @throws ApellidosInvalidos
   * @throws CedulaInvalida
   * @throws FechaInvalida
   * @throws DireccionInvalida
   * @throws TelefonoInvalido
   * @throws CorreoInvalido
   * @throws UsuarioInvalido
   * @throws ClaveInvalida
   */
  static function instanciar(
    string $nombres,
    string $apellidos,
    int $cedula,
    FechaNacimiento $fechaNacimiento,
    Genero $genero,
    string $direccion,
    string $telefono,
    string $correo,
    string $apodo,
    string $clave,
    Rol $rol
  ): self {
    return new self(
      ID::crearAleatorio(),
      Nombres::instanciar($nombres),
      Apellidos::instanciar($apellidos),
      new Cedula($cedula),
      $fechaNacimiento,
      $genero,
      new Direccion($direccion),
      new Telefono($telefono),
      new Correo($correo),
      new Apodo($apodo),
      Clave::encriptar($clave),
      $rol,
      FechaHora::actual()
    );
  }

  function estaActivo(): bool {
    return $this->estaActivo;
  }

  function nombres(): string {
    return $this->nombres;
  }

  function apellidos(): string {
    return $this->apellidos;
  }

  function cedula(): int {
    return (int) $this->cedula->__toString();
  }

  function apodo(): string {
    return $this->apodo;
  }

  function clave(): string {
    return $this->clave;
  }

  function rol(): Rol {
    return $this->rol;
  }

  function fechaNacimiento(string $formato): string {
    return $this->fechaNacimiento->formatear($formato);
  }

  function direccion(): string {
    return $this->direccion;
  }

  function telefono(): string {
    return $this->telefono;
  }

  function correo(): string {
    return $this->correo;
  }

  function claveEsValida(string $clave): bool {
    return $this->clave->esValida($clave);
  }
}
