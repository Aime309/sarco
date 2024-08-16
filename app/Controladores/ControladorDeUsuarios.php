<?php

namespace SARCO\Controladores;

use SARCO\App;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Usuario;
use SARCO\Repositorios\RepositorioDeUsuarios;

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
    $resultado = $this->repositorio->guardar($usuario);

    if ($resultado->error) {
      $_SESSION['mensajes.error'] = $resultado->error;
      $_SESSION['datos'] = $usuario;

      if ($rol->esIgualA(Rol::Docente) && !$resultado->error) {
        App::redirect('/maestros');
      } else {
        App::redirect(App::request()->referrer);
      }

      return;
    }

    $mensaje = $genero === Genero::Femenino ? 'registrada' : 'registrado';
    $_SESSION['mensajes.exito'] = "{$rol->obtenerPorGenero($genero)} $mensaje exitósamente";
    unset($_SESSION['datos']);
    App::redirect('/usuarios');
  }

  function mostrarFormularioDeRegistro(): void {
    $rol = $_GET['rol'] ?? 'usuario';

    $titulo = strtolower($rol) === 'maestro' ? 'Nuevo maestro' : 'Nuevo usuario';

    App::render('paginas/usuarios/nuevo', [], 'pagina');
    App::render('plantillas/privada', ['titulo' => $titulo]);
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

  function mostrarFormularioDeRestablecerClave(int $cedula): void {
    $usuario = $this->repositorio->buscar($cedula);

    App::render('paginas/usuarios/restablecer', compact('usuario'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Restablecer contraseña']);
  }

  function restablecerClave(int $cedula) {
    $contraseñas = App::request()->data->getData();

    if ($contraseñas['nueva_clave'] !== $contraseñas['confirmar_clave']) {
      $this->redirigirConError('Ambas contraseñas deben coincidir');
    }

    $usuario = $this->repositorio->buscar($cedula);

    if (!$usuario) {
      $this->redirigirConError('Usuario no encontrado');
    }

    $usuario->cambiarClave($contraseñas['nueva_clave']);
    $resultado = $this->repositorio->actualizar($usuario->id, $usuario);

    if ($resultado->tuvoExito) {
      $_SESSION['mensajes.exito'] = 'Contraseña actualizada exitósamente';
      unset($_SESSION['datos.contraseñas']);
      App::redirect('/usuarios');
    } else {
      $this->redirigirConError('Ha ocurrido un error, intente con otra contraseña');
    }
  }

  private function redirigirConError(string $error) {
    $_SESSION['mensajes.error'] = $error;
    $_SESSION['datos.contraseñas'] = App::request()->data->getData();
    App::redirect(App::request()->referrer);
  }
}
