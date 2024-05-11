<?php

declare(strict_types=1);

namespace SARCO\Shared\Domain;

use Stringable;

final readonly class Phone implements Stringable {
  private const SEPARATORS = '(\s|-)?';
  public string $value;

  function __construct(string $value) {
    self::validate($value);

    $this->value = self::format($value);
  }

  function __toString(): string {
    return $this->value;
  }

  private static function validate(string $value): void {
    $isInternational = self::isInternational($value);
    $isVenezuelan = self::isVenezuelan($value);

    if (!$isInternational && !$isVenezuelan) {
      throw new InvalidPhone;
    }
  }

  private static function isInternational(string $value): bool {
    static $pattern = '/^'
      . '\+[0-9]{2}'
      . self::SEPARATORS
      . '[0-9]{3}'
      . self::SEPARATORS
      . '[0-9]{4}'
      . self::SEPARATORS
      . '[0-9]{3}'
      . '$/';

    return (bool) preg_match($pattern, $value);
  }

  private static function isVenezuelan(string $value): bool {
    static $pattern = '/^'
      . '[0-9]{4}'
      . self::SEPARATORS
      . '[0-9]{3}'
      . self::SEPARATORS
      . '[0-9]{4}'
      . '$/';

    return (bool) preg_match($pattern, $value);
  }

  private static function format(string $value): string {
    $value = str_replace([' ', '-'], '', $value);

    if (self::isVenezuelan($value)) {
      return self::convertVenezuelanToInternational($value);
    }

    return self::convertToWhatsAppFormat($value);
  }

  private static function convertVenezuelanToInternational(
    string $value
  ): string {
    static $venezuelanCodes = [416, 426, 424, 414, 412];
    $phoneCompanyCode = (int) substr($value, 0, 4);
    $assignedCode = substr($value, 4);

    if (in_array($phoneCompanyCode, $venezuelanCodes)) {
      return "+58 $phoneCompanyCode-$assignedCode";
    }

    return $value;
  }

  private static function convertToWhatsAppFormat(string $value): string {
    $countryCode = substr($value, 1, 2);
    $phoneCompanyCode = substr($value, 3, 3);
    $assignedCode = substr($value, 6);

    return "+$countryCode $phoneCompanyCode-$assignedCode";
  }
}
