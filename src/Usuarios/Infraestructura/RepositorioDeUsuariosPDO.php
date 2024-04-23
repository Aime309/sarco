<?php

namespace SARCOV2\Usuarios\Infraestructura;

use PDO;
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
use SARCOV2\Usuarios\Dominio\{
  Apodo,
  Clave,
  RepositorioDeUsuarios,
  Rol,
  Usuario,
  Usuarios
};

final readonly class RepositorioDeUsuariosPDO implements RepositorioDeUsuarios {
  function __construct(private PDO $conexion) {
    $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }

  function obtenerTodos(): Usuarios {
    $coleccion = new Usuarios;
    $consulta = "SELECT * FROM {$this->tabla()}";

    foreach ($this->conexion->query($consulta) as $info) {
      $usuario = new Usuario(
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

      $coleccion->añadir($usuario);
    }

    return $coleccion;
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
    $sentencia->execute();
  }

  private function tabla(): string {
    return 'usuarios';
  }
}
