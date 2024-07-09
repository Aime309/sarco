<?php

namespace SARCO\Controladores;

use PDO;
use PDOException;
use SARCO\App;
use SARCO\Enumeraciones\Rol;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Usuario;
use SARCO\Repositorios\RepositorioDeBoletines;
use SARCO\Repositorios\RepositorioDeUsuarios;

final readonly class ControladorDeBoletines {
  function __construct(
    private PDO $pdo,
    private RepositorioDeBoletines $repositorioDeBoletines,
    private RepositorioDeUsuarios $repositorioDeUsuarios
  ) {
  }

  function mostrarListado(): void {
    $boletines = $this->repositorioDeBoletines->todos();

    App::render('paginas/boletines/listado', compact('boletines'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Boletines']);
  }

  function mostrarEdicion(string $id): void {
    $boletin = $this->repositorioDeBoletines->buscar($id);

    App::render('paginas/boletines/editar', compact('boletin'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Editar boletín']);
  }

  function actualizar(string $id): void {
    $boletin = App::request()->data->getData();

    $sentencia = $this->pdo->prepare("
      UPDATE boletines SET numero_inasistencias = :inasistencias,
      nombre_proyecto = :proyecto, descripcion_formacion = :formacion,
      descripcion_ambiente = :ambiente, recomendaciones = :recomendaciones
      WHERE id = '$id'
    ");

    $sentencia->bindValue(':inasistencias', $boletin['inasistencias'], PDO::PARAM_INT);
    $sentencia->bindValue(':proyecto', $boletin['proyecto']);
    $sentencia->bindValue(':formacion', $boletin['formacion']);
    $sentencia->bindValue(':ambiente', $boletin['ambiente']);
    $sentencia->bindValue(':recomendaciones', $boletin['recomendaciones']);

    try {
      $sentencia->execute();
      $_SESSION['mensajes.exito'] = 'Boletín actualizado exitósamente';
      App::redirect('/estudiantes/boletines');
    } catch (PDOException $error) {
      throw $error;
    }
  }

  function imprimir(string $id): void {
    $boletines = $this->repositorioDeBoletines->todosDelMismoPeriodoPorId($id);
    $directores = $this->repositorioDeUsuarios->todosPorRol(Rol::Director);

    $directoresActivos = array_filter(
      $directores,
      fn (Usuario $director) => $director->estaActivo
    );

    [$director] = $directoresActivos;
    $titulo = 'Detalles de boletín';

    App::render('paginas/boletines/detalles', compact(
      'boletines',
      'titulo',
      'director'
    ));
  }
}
