<?php

namespace SARCO\Repositorios;

use PDO;
use PDOException;
use Resultado;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;
use Symfony\Component\Uid\UuidV4;

final readonly class RepositorioDeUsuarios {
  function __construct(private PDO $pdo) {
  }

  /** @return Usuario[] */
  function todos(): array {
    return bd()->query("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, direccion, telefono, correo,
      rol, esta_activo as estaActivo, fecha_registro as fechaRegistro
      FROM usuarios
    ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);
  }

  /**
   * @template T
   * @param T $usuario
   * @return Resultado<array>
   */
  function guardar(array $usuario): Resultado {
    $genero = Genero::from($usuario['genero']);
    $rol = Rol::from($usuario['rol']);
    $clave = Usuario::encriptar($usuario['clave']);

    $sentencia = $this->pdo->prepare("
      INSERT INTO usuarios (
        id, nombres, apellidos, cedula, fecha_nacimiento, genero, telefono,
        correo, direccion, clave, rol
      ) VALUES (
        :id, :nombres, :apellidos, :cedula, :fechaNacimiento, :genero,
        :telefono, :correo, :direccion, :clave, :rol
      )
    ");

    try {
      $sentencia->execute([
        ':id' => new UuidV4,
        ':nombres' => $usuario['nombres'],
        ':apellidos' => $usuario['apellidos'],
        ':cedula' => $usuario['cedula'],
        ':fechaNacimiento' => $usuario['fecha_nacimiento'],
        ':genero' => $genero->value,
        ':telefono' => $usuario['telefono'],
        ':correo' => $usuario['correo'],
        ':direccion' => $usuario['direccion'],
        ':clave' => $clave,
        ':rol' => $rol->value,
      ]);

      return Resultado::exito($usuario);
    } catch (PDOException $error) {
      if (str_contains($error, 'usuarios.nombres')) {
        return Resultado::fallo("Usuario {$usuario['nombres']} {$usuario['apellidos']} ya existe");
      } elseif (str_contains($error, 'usuarios.cedula')) {
        return Resultado::fallo("Usuario {$usuario['cedula']} ya existe");
      } elseif (str_contains($error, 'usuarios.telefono')) {
        return Resultado::fallo("Teléfono {$usuario['telefono']} ya existe");
      } elseif (str_contains($error, 'usuarios.correo')) {
        return Resultado::fallo("Correo {$usuario['correo']} ya existe");
      } else {
        throw $error;
      }
    }
  }

  function activar(int $cedula): void {
    $this->pdo->exec("
      UPDATE usuarios
      SET esta_activo = TRUE
      WHERE cedula = $cedula
    ");
  }

  function desactivar(int $cedula): void {
    $this->pdo->exec("
      UPDATE usuarios
      SET esta_activo = FALSE
      WHERE cedula = $cedula
    ");
  }
}
