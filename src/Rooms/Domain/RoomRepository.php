<?php

declare(strict_types=1);

namespace SARCO\Rooms\Domain;

interface RoomRepository {
  function save(Room $room): void;
}
