<?php

declare(strict_types=1);

namespace SARCO\Representatives\Domain;

use SARCO\Shared\Domain\ContactablePerson;
use SARCO\Students\Domain\Student;

final class Representative extends ContactablePerson {
  private CivilStatus $civilStatus;
  private Nationality $nationality;

  /** @var Student[] */
  private array $children = [];

  function __construct(
    private readonly int $id,
    string $names,
    string $lastNames,
    int $idCard,
    string $gender,
    string $birthDate,
    string $phone,
    string $email,
    string $registeredDate,
    string $civilStatus,
    string $nationality,
    Student $child,
    Student ...$children
  ) {
    parent::__construct(
      $names,
      $lastNames,
      $idCard,
      $gender,
      $birthDate,
      $phone,
      $email,
      $registeredDate
    );

    $this->civilStatus = CivilStatus::from($civilStatus);
    $this->nationality = Nationality::from($nationality);
    $this->children[] = $child;

    foreach ($children as $child) {
      if (!in_array($child, $this->children)) {
        $this->children[] = $child;
      } else {
        // TODO: throw error and switch to guard clause
      }
    }
  }

  function civilStatus(): string {
    return $this->civilStatus->toString($this->gender);
  }

  function nationality(): string {
    return $this->nationality->toString($this->gender);
  }
}
