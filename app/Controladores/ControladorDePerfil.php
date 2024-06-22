<?php

namespace SARCO\Controladores;

use PDO;
use SARCO\App;
use SARCO\Modelos\Usuario;
use SARCO\Repositorios\RepositorioDeUsuarios;

final readonly class ControladorDePerfil {
  function __construct(private RepositorioDeUsuarios $repositorio) {}

  function mostrarFormularioDeEdicion(): void {
    App::render('paginas/usuarios/perfil', [], 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Mi perfil']);
  }

  function actualizarPerfil(): void {
    $usuarioAutenticado = obtenerComo(App::view()->get('usuario'), Usuario::class);
    $datos = App::request()->data->getData();
    $resultado = $this->repositorio->actualizar($usuarioAutenticado->id, $datos);

    if ($resultado->error) {
      $_SESSION['mensajes.error'] = $resultado->error;
    } else {
      $_SESSION['mensajes.exito'] = 'Perfil actualizado exitósamente';
    }

    App::redirect('/perfil');
  }

  function actualizarClave(): void {
    $claves = App::request()->data->getData();
    $usuario = obtenerComo(App::view()->get('usuario'),  Usuario::class);
    $nuevaClave = Usuario::encriptar($claves['nueva_clave']);

    if (!$usuario->validarClave($claves['antigua_clave'])) {
      $_SESSION['mensajes.error'] = 'Antigua contraseña incorrecta';
    } elseif ($claves['antigua_clave'] === $claves['nueva_clave']) {
      $_SESSION['mensajes.error'] = 'La nueva contraseña no puede ser igual a la anterior';
    } elseif ($claves['nueva_clave'] !== $claves['confirmar_clave']) {
      $_SESSION['mensajes.error'] = 'La nueva contraseña y su confirmación no coinciden';
    }

    if (key_exists('mensajes.error', $_SESSION)) {
      App::redirect('/perfil');

      return;
    }

    $sentencia = bd()
      ->prepare('UPDATE usuarios SET clave = :clave WHERE cedula = :cedula');

    $sentencia->bindValue(':clave', $nuevaClave);
    $sentencia->bindValue(':cedula', $usuario->cedula, PDO::PARAM_INT);
    $sentencia->execute();

    $_SESSION['mensajes.exito'] = 'Contraseña actualizada exitósamente';
    App::redirect('/perfil');
  }

  function desactivarPerfil(): void {
    $usuario = obtenerComo(App::view()->get('usuario'), Usuario::class);
    $this->repositorio->desactivar($usuario->cedula);

    $_SESSION['mensajes.exito'] = 'Cuenta desactivada existósamente';
    App::redirect('/salir');
  }
}
