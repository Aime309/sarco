<?php

namespace SARCOV2\Usuarios\Infraestructura;

use PDO;
use PDOException;
use SARCOV2\Compartido\Dominio\{Apellidos, Cedula, Correo, Direccion};
use SARCOV2\Compartido\Dominio\Excepciones\CedulaDuplicada;
use SARCOV2\Compartido\Dominio\Excepciones\CorreoDuplicado;
use SARCOV2\Compartido\Dominio\Excepciones\NombreCompletoDuplicado;
use SARCOV2\Compartido\Dominio\Excepciones\TelefonoDuplicado;
use SARCOV2\Compartido\Dominio\{FechaHora, FechaNacimiento, Genero, ID, Nombres, Telefono};
use SARCOV2\Usuarios\Dominio\{Apodo, Clave, Rol, Usuario, UsuarioNoExiste, Usuarios};
use SARCOV2\Usuarios\Dominio\Excepciones\UsuarioDuplicado;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;

final readonly class RepositorioDeUsuariosPDO implements RepositorioDeUsuarios {
  function __construct(private PDO $conexion) {
    $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }

  function obtenerTodos(): Usuarios {
    $coleccion = new Usuarios;
    $consulta = "SELECT * FROM {$this->tabla()}";

    foreach ($this->conexion->query($consulta) as $info) {
      $coleccion->añadir($this->mapear($info));
    }

    return $coleccion;
  }

  function obtenerTodosPorRol(Rol $rol): Usuarios {
    $coleccion = new Usuarios;
    $consulta = "SELECT * FROM {$this->tabla()} WHERE rol = '$rol->value'";

    foreach ($this->conexion->query($consulta) as $info) {
      $coleccion->añadir($this->mapear($info));
    }

    return $coleccion;
  }

  function buscarPorCedula(Cedula $cedula): ?Usuario {
    $consulta = "SELECT * FROM {$this->tabla()} WHERE cedula = $cedula";
    $resultado = $this->conexion->query($consulta);

    $info = $resultado->fetch();

    if (!$info) {
      return null;
    }

    return $this->mapear($info);
  }

  function encontrarPorCedula(Cedula $cedula): Usuario {
    return $this->buscarPorCedula($cedula) ?? throw new UsuarioNoExiste;
  }

  function guardar(Usuario $usuario): void {
    $sentencia = $this->conexion->prepare("
      INSERT INTO {$this->tabla()} (
        nombres, apellidos, cedula, usuario, clave, rol, fecha_nacimiento,
        direccion, telefono, correo
      ) VALUES (
        :nombres, :apellidos, :cedula, :usuario, :clave, :rol, :fechaNacimiento,
        :direccion, :telefono, :correo
      )
    ");

    $sentencia->bindValue(':nombres', $usuario->nombres());
    $sentencia->bindValue(':apellidos', $usuario->apellidos());
    $sentencia->bindValue(':cedula', $usuario->cedula(), PDO::PARAM_INT);
    $sentencia->bindValue(':usuario', $usuario->apodo());
    $sentencia->bindValue(':clave', $usuario->clave());
    $sentencia->bindValue(':rol', $usuario->rol()->value);
    $sentencia->bindValue(':fechaNacimiento', $usuario->fechaNacimiento('Y-m-d'));
    $sentencia->bindValue(':direccion', $usuario->direccion());
    $sentencia->bindValue(':telefono', $usuario->telefono());
    $sentencia->bindValue(':correo', $usuario->correo());

    try {
      $sentencia->execute();
    } catch (PDOException $excepcion) {
      static $excepciones = [
        'nombres' => NombreCompletoDuplicado::class,
        'cedula' => CedulaDuplicada::class,
        'usuario' => UsuarioDuplicado::class,
        'telefono' => TelefonoDuplicado::class,
        'correo' => CorreoDuplicado::class
      ];

      $mensaje = $excepcion->getMessage();
      $tabla = $this->tabla();

      foreach ($excepciones as $campo => $clase) {
        if (str_contains($mensaje, "$tabla.$campo")) {
          throw new $clase;
        }
      }

      throw $excepcion;
    }
  }

  private function tabla(): string {
    return 'usuarios';
  }

  private function mapear(array $info): Usuario {
    return new Usuario(
      new ID($info['id']),
      Nombres::instanciar($info['nombres']),
      Apellidos::instanciar($info['apellidos']),
      new Cedula($info['cedula']),
      FechaNacimiento::instanciar('Y-m-d', $info['fecha_nacimiento']),
      Genero::Masculino,
      new Direccion($info['direccion']),
      new Telefono($info['telefono']),
      new Correo($info['correo']),
      new Apodo($info['usuario']),
      new Clave($info['clave']),
      Rol::Docente,
      FechaHora::instanciar('Y-m-d H:i:s', $info['fecha_registro']),
      $info['esta_activo']
    );
  }
}
