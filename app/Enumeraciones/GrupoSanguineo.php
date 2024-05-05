<?php

namespace SARCO\Enumeraciones;

enum GrupoSanguineo: string {
  case PositiveA = 'A+';
  case NegativeA = 'A-';

  case PositiveB = 'B+';
  case NegativeB = 'B-';

  case PositiveAB = 'AB+';
  case NegativeAB = 'AB-';

  case Positive0 = '0+';
  case Negative0 = '0-';

  use EnumUtils;
}
