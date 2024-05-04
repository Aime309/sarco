<?php

use SARCO\Modelos\Sala;

$salas = (fn (Sala ...$salas) => $salas)(...$salas);

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de salas
  </h3>
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
          <th>ID</th>
          <th>Nombre</th>
          <th>Edad mínima</th>
          <th>Edad máxima</th>
          <th>Fecha de registro</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($salas as $sala) : ?>
          <tr class="text-center">
            <td><?= $sala->id ?></td>
            <td><?= $sala ?></td>
            <td><?= $sala->edadMinima ?></td>
            <td><?= $sala->edadMaxima ?></td>
            <td><?= $sala->fechaRegistro() ?></td>
            <td>
              <a href="./salas/<?= $sala->id ?>" class="btn btn-success">
                Editar
              </a>
            </td>
            <td>
              <?php if ($sala->estaActiva) : ?>
                <a href="./salas/<?= $sala->id ?>/inhabilitar" class="btn btn-danger">
                  Inhabilitar
                </a>
              <?php else : ?>
                <a href="./salas/<?= $sala->id ?>/habilitar" class="btn btn-success">
                  Habilitar
                </a>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
