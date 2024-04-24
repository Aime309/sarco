<?php

use Leaf\BareUI;

function renderizar(string $vista, string $titulo, string $plantilla = 'basica', array $datos = []): never {
  $scripts = function (?string $script = null): array {
    static $scripts = [];

    if ($script) {
      $scripts[] = $script;
    }

    return $scripts;
  };

  $datos['scripts'] = $scripts;
  $datos['error'] ??= @$_SESSION['error'];
  $datos['success'] ??= @$_SESSION['success'];
  unset($_SESSION['error']);
  unset($_SESSION['success']);
  $pagina = BareUI::render("paginas/$vista", $datos);

  exit(BareUI::render("plantillas/$plantilla", compact('titulo', 'pagina', 'scripts') + $datos));
}
