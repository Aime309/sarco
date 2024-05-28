<?php

namespace SARCO\Controladores;

use PDO;
use PDOException;
use SARCO\App;
use SARCO\Modelos\Boletin;

final readonly class ControladorDeBoletines {
  function __construct(private PDO $pdo) {
  }

  function indice(): void {
    $boletines = bd()->query("
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

  function editar(string $id): void {
    $boletin = bd()->query("
      SELECT b.id, numero_inasistencias as inasistencias,
      nombre_proyecto as proyecto, descripcion_formacion as descripcionFormacion,
      descripcion_ambiente as descripcionAmbiente, recomendaciones,
      b.fecha_registro as fechaRegistro, e.nombres as nombresEstudiante,
      e.apellidos as apellidosEstudiante, e.cedula_escolar as cedulaEstudiante,
      m.numero_momento as momento FROM boletines b JOIN estudiantes e
      JOIN momentos m ON b.id_estudiante = e.id AND b.id_momento = m.id
      WHERE b.id = '$id'
    ")->fetchObject(Boletin::class);

    App::render('paginas/boletines/editar', compact('boletin'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Editar boletín']);
  }

  function actualizar(string $id): void {
    $boletin = App::request()->data->getData();

    $sentencia = bd()->prepare("
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
}
