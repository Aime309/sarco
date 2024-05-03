<?php

use SARCO\Modelos\Representante;

$representantes = (fn (Representante ...$representantes) => $representantes)(...$representantes);

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de representantes
  </h3>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="./representantes/nuevo">
        <i class="fas fa-plus fa-fw"></i>
        &nbsp; Nuevo representante
      </a>
    </li>
    <li>
      <a class="active" href="./representantes">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de representantes
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-dark table-sm">
      <thead>
        <tr class="text-center roboto-medium">
          <th>Cédula</th>
          <th>Nombre completo</th>
          <th>Edad</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($representantes as $representante) : ?>
          <tr class="text-center">
            <td><?= $representante->cedula ?></td>
            <td><?= $representante->nombreCompleto() ?></td>
            <td><?= $representante->edad() ?></td>
            <td><?= $representante->telefono ?></td>
            <td><?= $representante->correo ?></td>
            <td>
              <a href="./representantes/<?= $representante->cedula ?>/editar" class="btn btn-success">
                Editar
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
