<?php

use SARCO\Modelos\Usuario;

assert($usuario instanceof Usuario);

$maestros = (fn (Usuario ...$maestros) => $maestros)(...$maestros);

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de maestros
  </h3>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <?php if (!$usuario->esDocente()) : ?>
      <li>
        <a href="./usuarios/nuevo?rol=maestro">
          <i class="fas fa-plus fa-fw"></i>
          &nbsp; Nuevo maestro
        </a>
      </li>
    <?php endif ?>
    <li>
      <a class="active" href="./maestros/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de maestros
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
          <th>Dirección</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($maestros as $maestroIterado) : ?>
          <tr class="text-center">
            <td><?= $maestroIterado->cedula ?></td>
            <td><?= $maestroIterado->nombreCompleto() ?></td>
            <td><?= $maestroIterado->edad() ?></td>
            <td><?= $maestroIterado->telefono ?></td>
            <td><?= $maestroIterado->correo ?></td>
            <td><?= $maestroIterado->direccion ?></td>
            <td>
              <?php if ($usuario->esDirector()) : ?>
                <?php if ($maestroIterado->estaActivo) : ?>
                  <a href="./usuarios/<?= $maestroIterado->cedula ?>/desactivar" class="btn btn-danger">
                    Desactivar
                  </a>
                <?php else : ?>
                  <a href="./usuarios/<?= $maestroIterado->cedula ?>/activar" class="btn btn-success">
                    Activar
                  </a>
                <?php endif ?>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
