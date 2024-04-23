<?php

namespace SARCO\Mediadores;

use Leaf\BareUI;
use Leaf\Http\Session;

final class ManejadorDeMensajes {
  static function capturarMensajes(): void {
    $error = Session::retrieve('error');
    $success = Session::retrieve('success');

    BareUI::config('params', compact('error', 'success'));
  }
}
