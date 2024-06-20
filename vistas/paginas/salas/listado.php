<?php

use SARCO\Modelos\Sala;

$salas = (fn (Sala ...$salas) => $salas)(...$salas);

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de salas
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="./salas/nueva">
        <i class="fas fa-plus fa-fw"></i>
        &nbsp; Aperturar sala
      </a>
    </li>
    <li>
      <a class="active" href="./salas/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de salas
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
          <th>Nombre</th>
          <th>Edad mínima</th>
          <th>Edad máxima</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($salas as $sala) : ?>
          <tr class="text-center">
            <td>
              <a href="./salas/<?= $sala->id ?>" class="btn btn-secondary">
                Detalles
              </a>
            </td>
            <td>Sala <?= $sala ?></td>
            <td>
              <?= $sala->edadMinima ? "$sala->edadMinima años" : '< 1 año' ?>
            </td>
            <td><?= $sala->edadMaxima ?> años</td>
            <td>
              <a href="./salas/<?= $sala->id ?>/editar" class="btn btn-success">
                Editar
              </a>
            </td>
            <td>
              <?php if ($sala->estaActiva) : ?>
                <a href="./salas/<?= $sala->id ?>/inhabilitar" class="btn btn-secondary">
                  Inhabilitar
                </a>
              <?php else : ?>
                <a href="./salas/<?= $sala->id ?>/habilitar" class="btn btn-success">
                  Habilitar
                </a>
              <?php endif ?>
            </td>
            <td>
              <?php if ($sala->sePuedeEliminar) : ?>
                <a
                  href="./salas/<?= $sala->id ?>/eliminar"
                  class="btn btn-danger">
                  Eliminar
                </a>
              <?php else : ?>
                <a
                  data-bs-toggle="popover"
                  data-content="Esta sala ya ha sido asignada"
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
