<?php

declare(strict_types=1);

namespace SARCO\Tests\Shared\Domain;

use PHPUnit\Framework\Attributes\Test;
use SARCO\Shared\Domain\InvalidLastNames;
use SARCO\Shared\Domain\LastNames;
use PHPUnit\Framework\TestCase;

final class LastNamesTest extends TestCase {
  #[Test]
  function can_instantiate_from_a_valid_argument(): void {
    $lastNames = new LastNames('Sánchez Guillén');

    self::assertSame('Sánchez', $lastNames->first);
    self::assertSame('Guillén', $lastNames->second);
  }

  function can_instantiate_from_a_single_name(): void {
    $lastNames = new LastNames('Sánchez');

    self::assertSame('Sánchez', $lastNames->first);
    self::assertNull($lastNames->second);
  }

  #[Test]
  function cannot_instantiate_a_short_last_name(): void {
    self::expectException(InvalidLastNames::class);

    new LastNames('Sá');
  }

  #[Test]
  function cannot_instantiate_a_long_last_name(): void {
    self::expectException(InvalidLastNames::class);

    new LastNames('Sánchezsánchezsánchez');
  }

  #[Test]
  function cannot_instantiate_with_a_short_second_last_name(): void {
    self::expectException(InvalidLastNames::class);

    new LastNames('Sánchez Gu');
  }

  #[Test]
  function cannot_instantiate_with_a_long_second_last_name(): void {
    self::expectException(InvalidLastNames::class);

    new LastNames('Sánchez Guilléguillénguillénguillén');
  }

  #[Test]
  function can_format_valid_last_names(): void {
    $lowerCase = new LastNames('sánchez guillén');
    $mixedCase = new LastNames('sÁnChEz GuIlLéN');

    $expected = 'Sánchez Guillén';

    self::assertSame("$lowerCase->first $lowerCase->second", $expected);
    self::assertSame("$mixedCase->first $mixedCase->second", $expected);
  }

  #[Test]
  function can_cast_as_string(): void {
    $bothNames = new LastNames('Sánchez Guillén');
    $onlyFirst = new LastNames('Sánchez');

    self::assertEquals('Sánchez Guillén', $bothNames);
    self::assertEquals('Sánchez', $onlyFirst);
  }
}
