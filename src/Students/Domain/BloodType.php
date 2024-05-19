<?php

declare(strict_types=1);

namespace SARCO\Students\Domain;

enum BloodType: string {
  case PositiveA = 'A+';
  case NegativeA = 'A-';

  case PositiveB = 'B+';
  case NegativeB = 'B-';

  case PositiveAB = 'AB+';
  case NegativeAB = 'AB-';

  case Positive0 = '0+';
  case Negative0 = '0-';
}
