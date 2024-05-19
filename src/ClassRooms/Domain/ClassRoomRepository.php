<?php

declare(strict_types=1);

namespace SARCO\ClassRooms\Domain;

interface ClassRoomRepository {
  function save(ClassRoom $classRoom): void;
}
