<?php

declare(strict_types=1);

namespace SARCO\Tests\Shared\Domain;

use PHPUnit\Framework\Attributes\Test;
use SARCO\Shared\Domain\Phone;
use PHPUnit\Framework\TestCase;

final class PhoneTest extends TestCase {
  #[Test]
  function can_instantiate_from_valid_venezuelan_phone(): void {
    $phone = new Phone('04165335826');

    self::assertSame('+58 416-5335826', $phone->value);
  }

  #[Test]
  function can_instantiate_from_valid_international_phone(): void {
    $phone = new Phone('+58 416-5335826');

    self::assertSame('+58 416-5335826', $phone->value);
  }

  #[Test]
  function can_be_formatted_an_casted_to_string(): void {
    $phone = new Phone('04165335826');

    self::assertEquals('+58 416-5335826', $phone);
  }
}
