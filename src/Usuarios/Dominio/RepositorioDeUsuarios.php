<?php

namespace SARCOV2\Usuarios\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\{
  CedulaDuplicada,
  CorreoDuplicado,
  NombreCompletoDuplicado,
  TelefonoDuplicado
};
use SARCOV2\Usuarios\Dominio\Excepciones\UsuarioDuplicado;

interface RepositorioDeUsuarios {
  function obtenerTodos(): Usuarios;
  function obtenerTodosPorRol(Rol $rol): Usuarios;

  /**
   * @throws NombreCompletoDuplicado
   * @throws CedulaDuplicada
   * @throws TelefonoDuplicado
   * @throws CorreoDuplicado
   * @throws UsuarioDuplicado
   */
  function guardar(Usuario $usuario): void;
}
