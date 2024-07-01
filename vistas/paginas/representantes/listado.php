<?php

use SARCO\Modelos\Representante;
use SARCO\Modelos\Usuario;

assert($usuario instanceof Usuario);
$representantes = (fn (Representante ...$representantes) => $representantes)(...$representantes);

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de representantes
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li class="mx-0">
      <a class="active" href="./estudiantes">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de estudiante
      </a>
    </li>
    <li class="mx-0">
      <a href="#buscar-representante" data-toggle="modal">
        <i class="fas fa-search fa-fw"></i>
        &nbsp; Buscar representante
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
            <td>
              <a href="./representantes/<?= $representante->cedula ?>" class="btn btn-secondary">
                Detalles
              </a>
            </td>
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
