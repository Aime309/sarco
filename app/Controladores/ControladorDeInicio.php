<?php

namespace SARCO\Controladores;

use PDO;
use SARCO\App;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;

final readonly class ControladorDeInicio {
  function __construct(private PDO $pdo) {
  }

  function indice(): void {
    $cantidadDeUsuarios = (int) $this->pdo
      ->query('SELECT COUNT(id) FROM usuarios')
      ->fetchColumn();

    $cantidadDeRepresentantes = (int) $this->pdo
      ->query('SELECT COUNT(id) FROM representantes')
      ->fetchColumn();

    $cantidadDeMaestros = (int) $this->pdo
      ->query("SELECT COUNT(id) FROM usuarios WHERE rol = 'Docente'")
      ->fetchColumn();

    $cantidadDeSalas = (int) $this->pdo
      ->query("SELECT COUNT(id) FROM salas")
      ->fetchColumn();

    $cantidadDeEstudiantes = (int) $this->pdo
      ->query("SELECT COUNT(id) FROM estudiantes")
      ->fetchColumn();

    $ultimoPeriodo = $this->pdo->query("
      SELECT id, anio_inicio as inicio, fecha_registro as fechaRegistro
      FROM periodos ORDER BY inicio DESC LIMIT 1
    ")->fetchObject(Periodo::class) ?: null;

    $ultimoMomento = null;

    if ($ultimoPeriodo instanceof Periodo) {
      $mesActual = (int) date('m');

      $ultimoMomento = $this->pdo->query("
        SELECT id, numero, mes_inicio as mesInicio,
        dia_inicio as diaInicio, fecha_registro as fechaRegistro,
        id_periodo as idPeriodo
        FROM momentos
        WHERE idPeriodo = '{$ultimoPeriodo->id}'
        AND mesInicio >= $mesActual
        ORDER BY mesInicio ASC
        LIMIT 1
      ")->fetchObject(Momento::class);
    }

    if ($ultimoMomento === false) {
      $ultimoMomento = null;
    }

    App::render(
      'paginas/inicio',
      compact(
        'cantidadDeUsuarios',
        'cantidadDeRepresentantes',
        'cantidadDeMaestros',
        'cantidadDeEstudiantes',
        'ultimoPeriodo',
        'ultimoMomento',
        'cantidadDeSalas',
      ),
      'pagina'
    );

    App::render('plantillas/privada', ['titulo' => 'Inicio']);
  }
}
