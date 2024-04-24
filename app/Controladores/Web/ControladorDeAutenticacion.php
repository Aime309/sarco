<?php

namespace SARCO\Controladores\Web;

use Leaf\Form;
use Leaf\Http\Request;
use Leaf\Http\Session;
use Leaf\Router;
use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\UsuarioNoExiste;

final readonly class ControladorDeAutenticacion {
  function __construct(private RepositorioDeUsuarios $repositorio) {
  }

  function procesarCredenciales() {
    $credenciales = Request::validate([
      'cedula' => 'number',
      'clave' => 'alphadash'
    ]);

    if ($errors = Form::errors()) {
      @$errors['cedula'] && Session::set('error', $errors['cedula'][0]);
      @$errors['clave'] && Session::set('error', $errors['clave'][0]);

      return Router::push('./');
    }

    try {
      $usuario = $this->repositorio->encontrarPorCedula(new Cedula($credenciales['cedula']));

      if (!$usuario->claveEsValida($credenciales['clave'])) {
        throw new UsuarioNoExiste;
      }

      Session::set('credenciales.cedula', $usuario->cedula());
      Router::push('./');
    } catch (UsuarioNoExiste) {
      Session::set('error', 'Cédula o contraseña incorrecta');
      Router::push('./');
    }
  }

  function mostrarIngreso(): void {
    renderizar('ingreso', 'Ingreso');
  }
}
