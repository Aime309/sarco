<?php

namespace SARCO\Enumeraciones;

use UnitEnum;

trait EnumUtils {
  function esIgualA(UnitEnum $enum): bool {
    return $enum === $this;
  }
}
