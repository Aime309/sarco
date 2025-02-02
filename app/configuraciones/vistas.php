<?php

use SARCO\App;
use SARCO\Modelos\Periodo;

require_once __DIR__ . '/base de datos.php';

App::view()->path = ROOT . '/vistas';
App::view()->preserveVars = false;
App::view()->set('vistas', App::view());
App::view()->set('root', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

App::view()->set('estudiantes', []);

App::view()->set('periodoActual', bd()->query("
  SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
  FROM periodos ORDER BY inicio DESC LIMIT 1
")->fetchObject(Periodo::class) ?: null);

App::view()->set(
  'puedeRestaurar',
  file_exists(__DIR__ . '/../../base de datos/sarco.db.backup')
);

/**
 * Opcionalmente añade una ruta a un archivo `JS` y devuelve todas las rutas
 * @return string[]
 */
function scripts(?string $rutaAlScript = null): array {
  /** @var string[] */
  static $rutas = [];

  if (is_string($rutaAlScript)) {
    $rutas[] = $rutaAlScript;
  }

  return $rutas;
}
