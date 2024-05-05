<?php

use SARCO\Modelos\Inscripcion;

$inscripciones = (fn (Inscripcion ...$inscripciones) => $inscripciones)(...$inscripciones);

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de inscripciones
  </h3>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="./estudiantes/inscribir">
        <i class="fas fa-plus fa-fw"></i>
        &nbsp; Inscribir estudiante
      </a>
    </li>
    <li>
      <a class="active" href="./inscripciones/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de inscripciones
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-dark table-sm">
      <thead>
        <tr class="text-center roboto-medium">
          <th>Momento</th>
          <th>Estudiante</th>
          <th>Maestro</th>
          <th>Fecha de registro</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($inscripciones as $inscripcion) : ?>
          <tr class="text-center">
            <td><?= $inscripcion->momento() ?></td>
            <td><?= $inscripcion->estudiante() ?></td>
            <td><?= $inscripcion->docente() ?></td>
            <td><?= $inscripcion->fechaRegistro() ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
