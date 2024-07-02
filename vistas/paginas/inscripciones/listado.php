<?php

use SARCO\Modelos\Inscripcion;
use SARCO\Modelos\Usuario;

/**
 * @var Inscripcion[] $inscripciones
 * @var Usuario $usuario
 */

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de inscripciones
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <?php if ($usuario->esSecretario()) : ?>
      <li>
        <a href="./estudiantes/inscribir">
          <i class="fas fa-plus fa-fw"></i>
          &nbsp; Inscribir estudiante
        </a>
      </li>
    <?php endif ?>
    <li>
      <a class="active" href="./inscripciones/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de inscripciones
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <input class="form-control mb-3" placeholder="Buscar estudiante..." oninput="w3.filterHTML('#inscripciones', '.inscripcion', this.value)">
  <div class="table-responsive">
    <table class="table table-dark table-sm" id="inscripciones">
      <thead>
        <tr class="text-center roboto-medium">
          <th onclick="w3.sortHTML('#inscripciones', '.inscripcion', 'td:nth-child(1)')">
            Período
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#inscripciones', '.inscripcion', 'td:nth-child(2)')">
            Estudiante
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#inscripciones', '.inscripcion', 'td:nth-child(3)')">
            Fecha de registro
            <i class="fa fa-sort"></i>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($inscripciones as $inscripcion) : ?>
          <tr class="text-center inscripcion">
            <td><?= $inscripcion->periodo() ?></td>
            <td>
              <a target="_blank" href="./estudiantes/<?= $inscripcion->cedulaEstudiante ?>">
                <?= $inscripcion->estudiante() ?>
              </a>
            </td>
            <td><?= $inscripcion->fechaRegistro() ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
