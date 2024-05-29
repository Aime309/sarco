<?php

namespace SARCO\Controladores;

use PDO;
use PDOException;
use SARCO\App;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;
use SARCO\Repositorios\RepositorioDeUsuarios;
use Symfony\Component\Uid\UuidV4;

final readonly class ControladorDeUsuarios {
  function __construct(private RepositorioDeUsuarios $repositorio) {
  }

  function mostrarListado(): void {
    $idAutenticado = App::view()->get('usuario')->id;
    $usuarios = $this->repositorio->todos();

    $usuarios = array_filter(
      $usuarios,
      fn (Usuario $usuario) => $usuario->id !== $idAutenticado
    );

    App::render('paginas/usuarios/listado', compact('usuarios'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Usuarios']);
  }

  function crearCuenta(): void {
    $usuario = App::request()->data->getData();
    $genero = Genero::from($usuario['genero']);
    $rol = Rol::from($usuario['rol']);
    $clave = password_hash($usuario['clave'], PASSWORD_DEFAULT);

    $sentencia = bd()->prepare("
      INSERT INTO usuarios (
        id, nombres, apellidos, cedula, fecha_nacimiento, genero, telefono,
        correo, direccion, clave, rol
      ) VALUES (
        :id, :nombres, :apellidos, :cedula, :fechaNacimiento, :genero,
        :telefono, :correo, :direccion, :clave, :rol
      )
    ");

    $sentencia->bindValue(':id', new UuidV4);
    $sentencia->bindValue(':nombres', $usuario['nombres']);
    $sentencia->bindValue(':apellidos', $usuario['apellidos']);
    $sentencia->bindValue(':cedula', $usuario['cedula'], PDO::PARAM_INT);
    $sentencia->bindValue(':fechaNacimiento', $usuario['fecha_nacimiento']);
    $sentencia->bindValue(':genero', $genero->value);
    $sentencia->bindValue(':telefono', $usuario['telefono']);
    $sentencia->bindValue(':correo', $usuario['correo']);
    $sentencia->bindValue(':direccion', $usuario['direccion']);
    $sentencia->bindValue(':clave', $clave);
    $sentencia->bindValue(':rol', $rol->value);

    try {
      $sentencia->execute();

      $mensaje = $genero === Genero::Femenino ? 'registrada' : 'registrado';
      $_SESSION['mensajes.exito'] = "{$rol->obtenerPorGenero($genero)} $mensaje exitósamente";
      unset($_SESSION['datos']);

      App::redirect('/usuarios');
    } catch (PDOException $error) {
      if (str_contains($error, 'usuarios.nombres')) {
        $_SESSION['mensajes.error'] = "Usuario {$usuario['nombres']} {$usuario['apellidos']} ya existe";
      } elseif (str_contains($error, 'usuarios.cedula')) {
        $_SESSION['mensajes.error'] = "Usuario {$usuario['cedula']} ya existe";
      } elseif (str_contains($error, 'usuarios.telefono')) {
        $_SESSION['mensajes.error'] = "Teléfono {$usuario['telefono']} ya existe";
      } elseif (str_contains($error, 'usuarios.correo')) {
        $_SESSION['mensajes.error'] = "Correo {$usuario['correo']} ya existe";
      } else {
        throw $error;
      }

      $_SESSION['datos'] = $usuario;

      if ($rol->esIgualA(Rol::Docente)) {
        App::redirect('/maestros');
      } else {
        App::redirect(App::request()->referrer);
      }
    }
  }

  function mostrarFormularioDeRegistro(): void {
    App::render('paginas/usuarios/nuevo', [], 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Nuevo usuario']);
  }

  function activar(int $cedula): void {
    $this->repositorio->activar($cedula);
    $_SESSION['mensajes.exito'] = 'Usuario activado existósamente';
    App::redirect(App::request()->referrer);
  }

  function desactivar(int $cedula): void {
    $this->repositorio->desactivar($cedula);
    $_SESSION['mensajes.exito'] = 'Usuario desactivado existósamente';
    App::redirect(App::request()->referrer);
  }
}
