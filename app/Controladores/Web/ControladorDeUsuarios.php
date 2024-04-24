<?php

namespace SARCO\Controladores\Web;

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
    $peticion = new Request;

    try {
      ($this->registrador)(
        $peticion->postData('nombres'),
        $peticion->postData('apellidos'),
        $peticion->postData('cedula'),
        FechaNacimiento::instanciar('Y-m-d', $peticion->postData('fecha_nacimiento')),
        Genero::from($peticion->postData('genero')),
        $peticion->postData('direccion'),
        $peticion->postData('telefono'),
        $peticion->postData('correo'),
        $peticion->postData('usuario'),
        $peticion->postData('clave'),
        Rol::Director
      );

      Session::set('success', 'Director registrado exitósamente');
      Router::push('./');
    } catch (InvalidArgumentException $excepcion) {
      Session::set('error', $excepcion->getMessage());
      Router::push('./registrate');
    }
  }
}
