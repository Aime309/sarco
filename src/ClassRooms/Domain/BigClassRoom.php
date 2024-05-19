<?php

declare(strict_types=1);

namespace SARCO\ClassRooms\Domain;

use InvalidArgumentException;

final class BigClassRoom extends ClassRoom {
  protected function validateCapacityRange(): void {
    parent::validateCapacityRange();

    if ($this->minimumCapacity !== 31) {
      throw new InvalidArgumentException('La capacidad mínima debe ser de 31');
    }

    if ($this->maximumCapacity !== 32) {
      throw new InvalidArgumentException('La capacidad máxima debe ser de 32');
    }
  }
}
