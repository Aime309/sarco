<?php

function renderizar(string $vista, string $titulo, string $plantilla = 'basica', array $datos = []): never {
  $datos['scripts'] = function (?string $script = null): array {
    static $scripts = [];

    if ($script) {
      $scripts[] = $script;
    }

    return $scripts;
  };

  Flight::render("paginas/$vista", $datos, 'pagina');
  exit(Flight::render("plantillas/$plantilla", compact('titulo')));
}
