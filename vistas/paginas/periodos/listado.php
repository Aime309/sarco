<?php

use SARCO\Modelos\Periodo;

$periodos = (fn (Periodo ...$periodos) => $periodos)(...$periodos);

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de períodos
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="./periodos/nuevo">
        <i class="fas fa-plus fa-fw"></i>
        &nbsp; Aperturar período
      </a>
    </li>
    <li>
      <a class="active" href="./periodos/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de períodos
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-dark table-sm">
      <thead>
        <tr class="text-center roboto-medium">
          <th></th>
          <th>Período</th>
          <th>1er Momento</th>
          <th>2do Momento</th>
          <th>3er Momento</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($periodos as $periodo) : ?>
          <tr class="text-center">
            <td>
              <?php if ($periodo == $periodos[0]) : ?>
                <span class="badge bg-dark text-white">Actual</span>
              <?php endif ?>
            </td>
            <td><?= $periodo ?></td>
            <td><?= $periodo->momento(1)->fechaCompleta() ?></td>
            <td><?= $periodo->momento(2)->fechaCompleta() ?></td>
            <td><?= $periodo->momento(3)->fechaCompleta() ?></td>
            <td>
              <a href="./periodos/<?= $periodo->inicio ?>/editar" class="btn btn-success">
                Editar
              </a>
            </td>
            <td>
              <?php if ($periodo->sePuedeEliminar) : ?>
                <a
                  href="./periodos/<?= $periodo->inicio ?>/eliminar"
                  class="btn btn-danger">
                  Eliminar
                </a>
              <?php else : ?>
                <a
                  data-bs-toggle="popover"
                  data-content="Este período ya tiene inscripciones o asignaciones registradas"
                  class="btn btn-danger disabled"
                  style="pointer-events: initial; cursor: not-allowed">
                  Eliminar
                </a>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
