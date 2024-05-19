<?php

declare(strict_types=1);

namespace SARCO\Rooms\Domain;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use SARCO\ClassRooms\Domain\ClassRoom;
use SARCO\ClassRooms\Domain\ExhaustedClassRoom;
use SARCO\ClassRooms\Domain\StudentAlreadyExists;
use SARCO\Shared\Domain\InvalidRegisteredDate;
use SARCO\Shared\Domain\RegisteredDate;
use SARCO\Students\Domain\Student;

final class Room {
  private RoomName $name;
  private RegisteredDate $registeredDate;

  function __construct(
    private readonly int $id,
    string $name,
    private int $minAge,
    private int $maxAge,
    string $registeredDate,
    private bool $isActive = true,
    private ?ClassRoom $classRoom = null
  ) {
    $this->name = RoomName::from($name);

    try {
      $registeredDate = new DateTimeImmutable($registeredDate);
    } catch (Exception) {
      throw new InvalidRegisteredDate;
    }

    $this->validateAgeRanges();
  }

  function assignClassRoom(ClassRoom $classRoom): void {
    $this->classRoom = $classRoom;
  }

  /**
   * @throws ExhaustedClassRoom
   * @throws StudentAlreadyExists
   */
  function addStudent(Student $student): void {
    $this->classRoom->addStudent($student);
  }

  private function validateAgeRanges(): void {
    if (
      $this->minAge < 0
      || $this->maxAge > 5
      || $this->minAge > $this->maxAge
    ) {
      throw new InvalidArgumentException('La edad mínima debe estar entre 0 y
      la edad máxima');
    }
  }

  function name(): string {
    return $this->name->value;
  }

  function registeredDate(string $format): string {
    return $this->registeredDate->value->format($format);
  }
}
