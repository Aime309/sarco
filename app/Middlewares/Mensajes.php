<?php

namespace SARCO\Middlewares;

use Leaf\BareUI;
use Leaf\Http\Session;

class Mensajes {
  static function capturarMensajes(): void {
    $error = Session::retrieve('error');
    $success = Session::retrieve('success');

    BareUI::config('params', compact('error', 'success'));
  }
}
