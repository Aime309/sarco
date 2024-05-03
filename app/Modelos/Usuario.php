<?php

namespace SARCO\Modelos;

final class Usuario extends Persona {
  public string $direccion;
  public string $rol;
  public bool $estaActivo;
}
