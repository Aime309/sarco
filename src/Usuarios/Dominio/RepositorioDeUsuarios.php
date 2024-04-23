<?php

namespace SARCOV2\Usuarios\Dominio;

interface RepositorioDeUsuarios {
  function obtenerTodos(): Usuarios;
  function guardar(Usuario $usuario): void;
}
