<?php

declare(strict_types=1);

namespace SARCO\ClassRooms\Domain;

use InvalidArgumentException;
use SARCO\Students\Domain\Student;

abstract class ClassRoom {
  /** @var Student[] */
  private array $students = [];

  /**
   * @throws InvalidArgumentException
   */
  function __construct(
    private readonly int $id,
    private readonly string $code,
    protected readonly int $minimumCapacity,
    protected readonly int $maximumCapacity,
    Student ...$students
  ) {
    $this->validateCapacityRange();

    foreach ($students as $student) {
      $this->addStudent($student);
    }
  }

  /** @throws InvalidArgumentException */
  protected function validateCapacityRange(): void {
    if ($this->minimumCapacity <= 0) {
      throw new InvalidArgumentException('La capacidad mínima debe ser mayor a
      cero');
    }

    if ($this->minimumCapacity > $this->maximumCapacity) {
      throw new InvalidArgumentException('La capacidad mínima debe ser mayor
      que la máxima');
    }
  }

  /**
   * @throws ExhaustedClassRoom
   * @throws StudentAlreadyExists
   */
  function addStudent(Student $student): void {
    if (count($this->students) + 1 > $this->maximumCapacity) {
      throw new ExhaustedClassRoom;
    }

    if (in_array($student, $this->students)) {
      throw new StudentAlreadyExists;
    }

    $this->students[] = $student;
  }
}
