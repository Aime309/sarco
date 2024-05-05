<?php

declare(strict_types=1);

namespace SARCO\Tests\Shared\Domain;

use PHPUnit\Framework\Attributes\Test;
use SARCO\Shared\Domain\InvalidNames;
use SARCO\Shared\Domain\Names;
use PHPUnit\Framework\TestCase;

final class NamesTest extends TestCase {
  #[Test]
  function can_instantiate_from_a_valid_argument(): void {
    $names = new Names('Franyer Adrián');

    self::assertSame('Franyer', $names->first);
    self::assertSame('Adrián', $names->second);
  }

  function can_instantiate_from_a_single_name(): void {
    $names = new Names('Franyer');

    self::assertSame('Franyer', $names->first);
    self::assertNull($names->second);
  }

  #[Test]
  function cannot_instantiate_a_short_name(): void {
    self::expectException(InvalidNames::class);

    new Names('Fr');
  }

  #[Test]
  function cannot_instantiate_a_long_name(): void {
    self::expectException(InvalidNames::class);

    new Names('Franyerfranyerfranyer');
  }

  #[Test]
  function cannot_instantiate_with_a_short_surname(): void {
    self::expectException(InvalidNames::class);

    new Names('Franyer Ad');
  }

  #[Test]
  function cannot_instantiate_with_a_long_surname(): void {
    self::expectException(InvalidNames::class);

    new Names('Franyer Adriánadrianadrianadrian');
  }

  #[Test]
  function can_format_valid_names(): void {
    $lowerCase = new Names('franyer adrián');
    $mixedCase = new Names('fRaNyEr AdRiáN');

    $expected = 'Franyer Adrián';

    self::assertSame("$lowerCase->first $lowerCase->second", $expected);
    self::assertSame("$mixedCase->first $mixedCase->second", $expected);
  }

  #[Test]
  function can_cast_as_string(): void {
    $bothNames = new Names('Franyer Adrián');
    $onlyFirst = new Names('Franyer');

    self::assertEquals('Franyer Adrián', $bothNames);
    self::assertEquals('Franyer', $onlyFirst);
  }
}
