<?php

namespace SARCO\Repositorios;

use PDO;
use PDOException;
use SARCO\Resultado;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;
use Symfony\Component\Uid\UuidV4;

final readonly class RepositorioDeUsuarios {
  function __construct(private PDO $pdo) {
  }

  /** @return Usuario[] */
  function todos(): array {
    return $this->pdo->query("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, genero, direccion, telefono, correo,
      rol, esta_activo as estaActivo, fecha_registro as fechaRegistro, clave
      FROM usuarios
    ")->fetchAll(PDO::FETCH_CLASS, Usuario::class);
  }

  function buscar(string $id): ?Usuario {
    $sentencia = $this->pdo->prepare("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, genero, direccion, telefono, correo,
      rol, esta_activo as estaActivo, fecha_registro as fechaRegistro, clave
      FROM usuarios WHERE id = ?
    ");

    $sentencia->execute([$id]);

    return $sentencia->fetchObject(Usuario::class) ?: null;
  }

  /**
   * @param array{
   *   genero: string,
   *   rol: string,
   *   clave: string,
   *   nombres: string,
   *   apellidos: string,
   *   cedula: int,
   *   fecha_nacimiento: string,
   *   telefono: string,
   *   correo: string,
   *   direccion: string
   * } $usuario
   * @return Resultado<null>
   */
  function guardar(array $usuario): Resultado {
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
        ':genero' => $usuario['genero'],
        ':telefono' => $usuario['telefono'],
        ':correo' => $usuario['correo'],
        ':direccion' => $usuario['direccion'],
        ':clave' => Usuario::encriptar($usuario['clave']),
        ':rol' => $usuario['rol']
      ]);

      return Resultado::exito(null);
    } catch (PDOException $error) {
      if (str_contains($error, 'usuarios.id')) {
        return Resultado::fallo('Ha ocurrido un error, por favor intente nuevamente');
      } elseif (str_contains($error, 'usuarios.nombres')) {
        return Resultado::fallo("Usuario {$usuario['nombres']} {$usuario['apellidos']} ya existe");
      } elseif (str_contains($error, 'usuarios.cedula')) {
        return Resultado::fallo("Cédula {$usuario['cedula']} ya existe");
      } elseif (str_contains($error, 'usuarios.telefono')) {
        return Resultado::fallo("Teléfono {$usuario['telefono']} ya existe");
      } elseif (str_contains($error, 'usuarios.correo')) {
        return Resultado::fallo("Correo {$usuario['correo']} ya existe");
      }

      return Resultado::fallo($error->getMessage());
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
