<?php

/**
 * @template T of mixed
 */
final readonly class Resultado {
  /**
   * @param T $valor
   */
  private function __construct(
    public mixed $valor,
    public bool $tuvoExito,
    public ?string $error
  ) {
  }

  /**
   * @template V of mixed
   * @param V $valor
   * @return self<V>
   */
  static function exito(mixed $valor): self {
    return new self($valor, true, null);
  }

  /**
   * @return self<null>
   */
  static function fallo(string $error): self {
    return new self(null, false, $error);
  }
}
