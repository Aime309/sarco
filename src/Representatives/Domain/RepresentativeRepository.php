<?php

declare(strict_types=1);

namespace SARCO\Representatives\Domain;

interface RepresentativeRepository {
  function save(Representative $representative): void;
}
