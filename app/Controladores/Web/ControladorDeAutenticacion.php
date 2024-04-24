<?php

namespace SARCO\Controladores\Web;

use Flight;
use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Usuarios\Dominio\Excepciones\ClaveInvalida;
use SARCOV2\Usuarios\Dominio\RepositorioDeUsuarios;
use SARCOV2\Usuarios\Dominio\UsuarioNoExiste;

final readonly class ControladorDeAutenticacion {
  function __construct(private RepositorioDeUsuarios $repositorio) {
  }

  function procesarCredenciales() {
    $credenciales = Flight::request()->data;

    try {
      $usuario = $this->repositorio->encontrarPorCedula(new Cedula($credenciales['cedula']));

      if (!$usuario->claveEsValida($credenciales['clave'])) {
        throw new ClaveInvalida($credenciales['clave']);
      }
    } catch (UsuarioNoExiste) {
      $_SESSION['error'] = 'Cédula o contraseña incorrecta';

      return Flight::redirect('/ingresar');
    }

    if (!$usuario->estaActivo()) {
      $_SESSION['error'] = 'Este usuario se encuentra desactivado';

      return Flight::redirect('/ingresar');
    }

    $_SESSION['credenciales.cedula'] = $usuario->cedula();
    Flight::redirect('/');
  }

  function mostrarIngreso(): void {
    renderizar('ingreso', 'Ingreso');
  }
}
