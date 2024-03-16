<?php

namespace SARCO\Middlewares;

use Leaf\Auth;
use Leaf\BareUI;

class Autenticacion {
  static function bloquearNoAutenticados(): void {
    if (!Auth::status()) {
      exit(BareUI::render('paginas/ingreso'));
    }
  }
}
