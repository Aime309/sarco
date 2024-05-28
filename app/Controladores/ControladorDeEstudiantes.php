<?php

namespace SARCO\Controladores;

use PDO;
use SARCO\App;
use SARCO\Modelos\Estudiante;

final readonly class ControladorDeEstudiantes {
  function __construct(private PDO $pdo) {
  }

  function indice(): void {
    $estudiantes = $this->pdo->query("
      SELECT id, nombres, apellidos, cedula,
      fecha_nacimiento as fechaNacimiento, lugar_nacimiento as lugarNacimiento,
      genero, tipo_sangre as grupoSanguineo, fecha_registro as fechaRegistro,
      id_mama as idMama, id_papa as idPapa FROM estudiantes
    ")->fetchAll(PDO::FETCH_CLASS, Estudiante::class);

    App::render('paginas/estudiantes/listado', compact('estudiantes'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Estudiantes']);
  }
}
