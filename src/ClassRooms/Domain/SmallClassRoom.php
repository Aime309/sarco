<?php

declare(strict_types=1);

namespace SARCO\ClassRooms\Domain;

use InvalidArgumentException;

final class SmallClassRoom extends ClassRoom {
  protected function validateCapacityRange(): void {
    parent::validateCapacityRange();

    if ($this->minimumCapacity !== 28) {
      throw new InvalidArgumentException('La capacidad mínima debe ser de 28');
    }

    if ($this->maximumCapacity !== 29) {
      throw new InvalidArgumentException('La capacidad máxima debe ser de 29');
    }
  }
}
