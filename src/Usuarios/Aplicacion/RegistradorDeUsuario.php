<?php

namespace SARCOV2\Usuarios\Aplicacion;

use SARCOV2\Compartido\Dominio\{FechaNacimiento, Genero};
use SARCOV2\Usuarios\Dominio\{RepositorioDeUsuarios, Rol, Usuario};

final readonly class RegistradorDeUsuario {
  function __construct(private RepositorioDeUsuarios $repositorio) {
  }

  function __invoke(
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
  ): void {
    $usuario = Usuario::instanciar(
      $nombres,
      $apellidos,
      $cedula,
      $fechaNacimiento,
      $genero,
      $direccion,
      $telefono,
      $correo,
      $apodo,
      $clave,
      $rol
    );

    $this->repositorio->guardar($usuario);
  }
}
