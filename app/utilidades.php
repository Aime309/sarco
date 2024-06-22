<?php

/**
 * @template T
 * @param class-string<T> $clase
 * @return T
 * @throws AssertionError
 */
function obtenerComo(mixed $valor, string $clase): object {
  assert(is_object($valor));
  assert($valor instanceof $clase);

  return $valor;
}
