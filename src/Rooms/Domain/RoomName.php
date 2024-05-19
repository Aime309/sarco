<?php

declare(strict_types=1);

namespace SARCO\Rooms\Domain;

enum RoomName: string {
  case Maternal = 'Maternal';
  case ThreeOnly = 'De 3 Única';
  case Mixed34 = 'Mixta 3-4';
  case FourOnly = 'De 4 Única';
  case Mixed45 = 'Mixta 4-5';
  case FiveOnly = 'De 5 Única';
}
