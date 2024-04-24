<?php

namespace SARCOV2\Usuarios\Dominio;

use SARCOV2\Compartido\Dominio\Cedula;
use SARCOV2\Compartido\Dominio\Excepciones\CedulaDuplicada;
use SARCOV2\Compartido\Dominio\Excepciones\CorreoDuplicado;
use SARCOV2\Compartido\Dominio\Excepciones\NombreCompletoDuplicado;
use SARCOV2\Compartido\Dominio\Excepciones\TelefonoDuplicado;
use SARCOV2\Usuarios\Dominio\Excepciones\UsuarioDuplicado;

interface RepositorioDeUsuarios {
  function obtenerTodos(): Usuarios;
  function obtenerTodosPorRol(Rol $rol): Usuarios;
  function buscarPorCedula(Cedula $cedula): ?Usuario;

  /** @throws UsuarioNoExiste */
  function encontrarPorCedula(Cedula $cedula): Usuario;

  /**
   * @throws NombreCompletoDuplicado
   * @throws CedulaDuplicada
   * @throws TelefonoDuplicado
   * @throws CorreoDuplicado
   * @throws UsuarioDuplicado
   */
  function guardar(Usuario $usuario): void;
}
