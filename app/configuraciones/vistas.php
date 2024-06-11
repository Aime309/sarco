<?php

use SARCO\App;
use SARCO\Modelos\Estudiante;

require_once __DIR__ . '/base de datos.php';

App::view()->path = ROOT . '/vistas';
App::view()->preserveVars = false;
App::view()->set('vistas', App::view());
App::view()->set('root', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

App::view()->set('estudiantes', bd()->query("
  SELECT id, nombres, apellidos, cedula,
  fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
  genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
  id_mama as idMama, id_papa as idPapa FROM estudiantes
")->fetchAll(PDO::FETCH_CLASS, Estudiante::class));

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
