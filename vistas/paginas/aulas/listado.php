<?php

use SARCO\Modelos\Aula;

$aulas = (static fn (Aula ...$aulas) => $aulas)(...$aulas);

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de aulas
  </h3>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="./aulas/nueva">
        <i class="fas fa-plus fa-fw"></i>
        &nbsp; Aperturar aula
      </a>
    </li>
    <li>
      <a class="active" href="./aulas/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de aulas
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-dark table-sm">
      <thead>
        <tr class="text-center roboto-medium">
          <th>Código</th>
          <th>Tipo</th>
          <th>Capacidad</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($aulas as $aula) : ?>
          <tr class="text-center">
            <td><?= $aula ?></td>
            <td>
              <?= $aula->tipo ?>
            </td>
            <td><?= $aula->capacidad() ?> niños</td>
            <td>
              <a href="./aulas/<?= $aula->codigo ?>" class="btn btn-success">
                Editar
              </a>
            </td>
            <td>
              <?php if ($aula->sePuedeEliminar) : ?>
                <a
                  href="./aulas/<?= $aula->codigo ?>/eliminar"
                  class="btn btn-danger">
                  Eliminar
                </a>
              <?php else : ?>
                <a
                  data-bs-toggle="popover"
                  data-bs-title="Esta aula ya ha sido asignada"
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
