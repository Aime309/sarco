<?php

namespace SARCO\Controladores;

use PDO;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Repositorios\RepositorioDeAulas;
use SARCO\Repositorios\RepositorioDePeriodos;
use SARCO\Repositorios\RepositorioDeSalas;
use SARCO\Repositorios\RepositorioDeUsuarios;

final readonly class ControladorDeSalas {
  function __construct(
    private RepositorioDePeriodos $repositorioDePeriodos,
    private RepositorioDeUsuarios $repositorioDeUsuarios,
    private RepositorioDeSalas $repositorioDeSalas,
    private RepositorioDeAulas $repositorioDeAulas
  ) {
  }

  function mostrarFormularioDeReasignar(string $id): void {
    $periodos = $this->repositorioDePeriodos->todos();
    $maestros = $this->repositorioDeUsuarios->todosPorRol(Rol::Docente);
    $aulas = $this->repositorioDeAulas->todas();
    $sala = $this->repositorioDeSalas->buscar($id);

    $asignaciones = bd()->query("
      SELECT id as idAsignacion, id_sala as idSala,
      id_aula as idAula, id_periodo as idPeriodo, id_docente1 as idDocente1,
      id_docente2 as idDocente2, id_docente3 as idDocente3
      FROM asignaciones_de_salas
    ")->fetchAll(PDO::FETCH_ASSOC);

    App::render('paginas/salas/reasignar', compact(
      'periodos',
      'maestros',
      'aulas',
      'asignaciones',
      'sala'
    ), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Reasignar sala']);
  }

  function reasignar(string $id): void {
  }
}
