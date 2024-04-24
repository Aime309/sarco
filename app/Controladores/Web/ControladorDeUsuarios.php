<?php

namespace SARCO\Controladores\Web;

use Flight;
use InvalidArgumentException;
use Leaf\Http\Request;
use Leaf\Http\Session;
use Leaf\Router;
use SARCOV2\Compartido\Dominio\{FechaNacimiento, Genero};
use SARCOV2\Usuarios\Aplicacion\RegistradorDeUsuario;
use SARCOV2\Usuarios\Dominio\Rol;

final readonly class ControladorDeUsuarios {
  function __construct(private RegistradorDeUsuario $registrador) {
  }

  function mostrarRegistroDirector(): void {
    renderizar('registro', 'Regístrate');
  }

  function registrarDirector(): void {
    $info = Flight::request()->data;

    try {
      ($this->registrador)(
        $info['nombres'],
        $info['apellidos'],
        $info['cedula'],
        FechaNacimiento::instanciar('Y-m-d', $info['fecha_nacimiento']),
        Genero::from($info['genero']),
        $info['direccion'],
        $info['telefono'],
        $info['correo'],
        $info['usuario'],
        $info['clave'],
        Rol::Director
      );

      $_SESSION['success'] = 'Director registrado exitósamente';
      Flight::redirect('/');
    } catch (InvalidArgumentException $excepcion) {
      $_SESSION['error'] = $excepcion->getMessage();
      Flight::redirect('/registrate');
    }
  }
}
