<?php

use SARCO\Modelos\Boletin;

$boletines = (fn (Boletin ...$boletines) => $boletines)(...$boletines);

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de boletines
  </h3>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a class="active" href="./estudiantes/boletines">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de boletines
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
          <th>Inasistencias</th>
          <th>Proyecto</th>
          <th>Fecha de registro</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($boletines as $boletin) : ?>
          <tr class="text-center">
            <td><?= $boletin->momento() ?></td>
            <td><?= $boletin->estudiante() ?></td>
            <td><?= $boletin->inasistencias ?></td>
            <td><?= $boletin->proyecto ?></td>
            <td><?= $boletin->fechaRegistro() ?></td>
            <td>
              <a href="./estudiantes/boletines/<?= $boletin->id ?>" class="btn btn-success">
                Editar
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>