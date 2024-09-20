<?php

use SARCO\Modelos\Boletin;

$boletines = (fn(Boletin ...$boletines) => $boletines)(...$boletines);

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de boletines
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <input class="form-control mb-3" placeholder="Buscar estudiante..." oninput="w3.filterHTML('#boletines', '.boletin', this.value)">
  <div class="table-responsive">
    <table class="table table-dark table-sm" id="boletines">
      <thead>
        <tr class="text-center roboto-medium">
          <th></th>
          <th onclick="w3.sortHTML('#boletines', '.boletin', 'td:nth-child(2)')">
            Momento
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#boletines', '.boletin', 'td:nth-child(3)')">
            Estudiante
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#boletines', '.boletin', 'td:nth-child(4)')">
            Inasistencias
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#boletines', '.boletin', 'td:nth-child(5)')">
            Proyecto
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#boletines', '.boletin', 'td:nth-child(6)')">
            Fecha de registro
            <i class="fa fa-sort"></i>
          </th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($boletines as $boletin) : ?>
          <tr class="text-center boletin">
            <td>
              <a href="./estudiantes/boletines/<?= $boletin->id ?>" target="_blank" class="btn btn-secondary">
                Imprimir
              </a>
            </td>
            <td><?= $boletin->momento() ?></td>
            <td><?= $boletin->estudiante() ?></td>
            <td><?= $boletin->inasistencias ?></td>
            <td><?= $boletin->proyecto ?></td>
            <td><?= $boletin->fechaRegistro() ?></td>
            <td>
              <a href="./estudiantes/boletines/<?= $boletin->id ?>/editar" class="btn btn-success">
                Llenar boletín
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
