<?php

namespace SARCOV2\Usuarios\Dominio;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/** @implements IteratorAggregate<int, Usuario> */
final class Usuarios implements IteratorAggregate, Countable {
  private array $usuarios = [];

  function __construct(Usuario ...$usuarios) {
    $this->usuarios = $usuarios;
  }

  function añadir(Usuario $usuario): self {
    $this->usuarios[] = $usuario;

    return $this;
  }

  function hayActivos(): bool {
    return array_filter(
      $this->usuarios,
      fn (Usuario $usuario): bool => $usuario->estaActivo()
    ) !== [];
  }

  function getIterator(): Traversable {
    return new ArrayIterator($this->usuarios);
  }

  function count(): int {
    return count($this->usuarios);
  }
}
