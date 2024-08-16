<?php

namespace SARCO\Repositorios;

use PDO;
use PDOException;
use SARCO\Resultado;
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

  private function buscarPor(string $campo, mixed $valor): ?Usuario {
    $sentencia = $this->pdo->prepare("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, genero, direccion, telefono, correo,
      rol, esta_activo as estaActivo, fecha_registro as fechaRegistro, clave
      FROM usuarios WHERE $campo = ?
    ");

    $sentencia->execute([$valor]);

    return $sentencia->fetchObject(Usuario::class) ?: null;
  }

  function buscar(int $cedula): ?Usuario {
    return $this->buscarPor('cedula', $cedula);
  }

  function buscarPorId(string $id): ?Usuario {
    return $this->buscarPor('id', $id);
  }

  /** @return Usuario[] */
  function todosPorRol(Rol $rol): array {
    $sentencia = $this->pdo->prepare("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, genero, direccion, telefono, correo,
      rol, esta_activo as estaActivo, fecha_registro as fechaRegistro, clave
      FROM usuarios WHERE rol = ?
    ");

    $sentencia->execute([$rol->value]);

    return $sentencia->fetchAll(PDO::FETCH_CLASS, Usuario::class);
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
        ':nombres' => mb_convert_case($usuario['nombres'], MB_CASE_TITLE),
        ':apellidos' => mb_convert_case($usuario['apellidos'], MB_CASE_TITLE),
        ':cedula' => $usuario['cedula'],
        ':fechaNacimiento' => $usuario['fecha_nacimiento'],
        ':genero' => $usuario['genero'],
        ':telefono' => $usuario['telefono'],
        ':correo' => $usuario['correo'],
        ':direccion' => mb_convert_case($usuario['direccion'], MB_CASE_TITLE),
        ':clave' => Usuario::encriptar($usuario['clave']),
        ':rol' => $usuario['rol']
      ]);

      return Resultado::exito(null);
    } catch (PDOException $error) {
      return self::controlarError($error, $usuario);
    }
  }

  /**
   * @param array{
   *   genero: string,
   *   nombres: string,
   *   apellidos: string,
   *   cedula: int,
   *   fecha_nacimiento: string,
   *   telefono: string,
   *   correo: string,
   *   direccion: string,
   *   clave: string
   * } | Usuario $datos
   * @return Resultado<null>
   */
  function actualizar(string $id, array|Usuario $datos): Resultado {
    if ($datos instanceof Usuario) {
      $datos = [
        'nombres' => $datos->nombres,
        'apellidos' => $datos->apellidos,
        'cedula' => $datos->cedula,
        'fechaNacimiento' => $datos->fechaNacimiento,
        'direccion' => $datos->direccion,
        'telefono' => $datos->telefono,
        'correo' => $datos->correo,
        'clave' => $datos->clave
      ];
    }

    $sentencia = $this->pdo->prepare("
      UPDATE usuarios SET nombres = :nombres, apellidos = :apellidos,
      cedula = :cedula, fecha_nacimiento = :fechaNacimiento,
      direccion = :direccion, telefono = :telefono, correo = :correo,
      clave = :clave
      WHERE id = :id
    ");

    try {
      $sentencia->execute([
        ':id' => $id,
        ':nombres' => $datos['nombres'],
        ':apellidos' => $datos['apellidos'],
        ':cedula' => $datos['cedula'],
        ':fechaNacimiento' => $datos['fechaNacimiento'],
        ':direccion' => $datos['direccion'],
        ':telefono' => $datos['telefono'],
        ':correo' => $datos['correo'],
        ':clave' => $datos['clave']
      ]);

      return Resultado::exito(null);
    } catch (PDOException $error) {
      return self::controlarError($error, $datos);
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

  private static function controlarError(
    PDOException $error,
    array | Usuario $usuario
  ): Resultado {
    if ($usuario instanceof Usuario) {
      $usuario = (array) $usuario;
    }

    if (str_contains($error, 'usuarios.id')) {
      return Resultado::fallo('Ha ocurrido un error, por favor intente nuevamente');
    } elseif (str_contains($error, 'usuarios.nombres')) {
      return Resultado::fallo("Usuario {$usuario['nombres']} {$usuario['apellidos']} ya existe");
    } elseif (str_contains($error, 'usuarios.cedula')) {
      return Resultado::fallo("Cédula {$usuario['cedula']} ya existe");
    } elseif (str_contains($error, 'usuarios.telefono')) {
      return Resultado::fallo("Teléfono {$usuario['telefono']} ya existe o es inválido (ej: +12 123-1234567)");
    } elseif (str_contains($error, 'usuarios.correo')) {
      return Resultado::fallo("Correo {$usuario['correo']} ya existe o es inválido");
    } elseif (str_contains($error, 'fecha_registro')) {
      return Resultado::fallo('Ha ocurrido un error, por favor sincronice la fecha y hora correctamente');
    } elseif (str_contains($error, 'fecha_nacimiento')) {
      return Resultado::fallo('El usuario debe haber nacido después del año 1906');
    } elseif (str_contains($error, 'genero')) {
      return Resultado::fallo('Género inválido, debe ser Masculino o Femenino');
    } elseif (str_contains($error, 'usuarios.direccion')) {
      return Resultado::fallo('Dirección inválida, debe tener al menos 3 letras');
    } elseif (str_contains($error, 'usuarios.clave')) {
      return Resultado::fallo('La clave debe tener al menos 8 caracteres');
    } elseif (str_contains($error, 'rol')) {
      return Resultado::fallo('Rol inválido, debe ser Director, Docente o Secretario');
    }

    return Resultado::fallo(trim($error->getMessage()));
  }
}
