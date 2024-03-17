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
  $pagina = BareUI::render("paginas/$vista", $datos);

  exit(BareUI::render("plantillas/$plantilla", compact('titulo', 'pagina', 'scripts')));
}
