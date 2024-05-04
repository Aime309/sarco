<?php

use SARCO\Modelos\Momento;

assert($ultimoMomento instanceof Momento);

$momentosParaIterar = [];

foreach ($momentos as $momento) {
  assert($momento instanceof Momento);

  $momentosParaIterar[$momento->periodo][] = $momento;
}

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de momentos
  </h3>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a class="active" href="./momentos/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de momentos
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <?php foreach ($momentosParaIterar as $periodo => $momentos) : ?>
    <div class="table-responsive">
      <table class="table table-dark table-sm">
        <thead>
          <tr class="text-center roboto-medium">
            <th><?= $periodo . '-' . $periodo + 1 ?></th>
            <th>#</th>
            <th>Inicio</th>
            <th>Fecha de registro</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($momentos as $momento) : ?>
            <tr class="text-center">
              <td>
                <?php if ($momento->id == $ultimoMomento->id) : ?>
                  <span class="badge bg-dark text-white">Actual</span>
                <?php endif ?>
              </td>
              <td><?= $momento ?></td>
              <td><?= $momento->inicio() ?></td>
              <td><?= $momento->fechaRegistro() ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  <?php endforeach ?>
</div>
