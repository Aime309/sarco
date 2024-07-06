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
    $boletines = $this->pdo->query("
      SELECT b.id, numero_inasistencias as inasistencias,
      nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente, recomendaciones,
      b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante, e.cedula as cedulaEstudiante,
      m.numero as momento, b.id_asignacion_sala as idAsignacion
      FROM boletines b
      JOIN estudiantes e
      JOIN momentos m
      ON b.id_estudiante = e.id
      AND b.id_momento = m.id
    ")->fetchAll(PDO::FETCH_CLASS, Boletin::class);

    App::render('paginas/boletines/listado', compact('boletines'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Boletines']);
  }

  function mostrarEdicion(string $id): void {
    $boletin = $this->pdo->query("
      SELECT b.id, numero_inasistencias as inasistencias,
      nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente, recomendaciones,
      b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante, e.cedula as cedulaEstudiante,
      m.numero as momento FROM boletines b JOIN estudiantes e
      JOIN momentos m ON b.id_estudiante = e.id AND b.id_momento = m.id
      WHERE b.id = '$id'
    ")->fetchObject(Boletin::class);

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
    $boletin = $this->repositorioDeBoletines->buscar($id);
    $directores = $this->repositorioDeUsuarios->todosPorRol(Rol::Director);

    $directoresActivos = array_filter(
      $directores,
      fn (Usuario $director) => $director->estaActivo
    );

    [$director] = $directoresActivos;
    $titulo = 'Detalles de boletín';

    App::render('paginas/boletines/detalles', compact(
      'boletin',
      'titulo',
      'director'
    ));
  }
}
