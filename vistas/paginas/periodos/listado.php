<?php

use SARCO\Modelos\Periodo;

$periodos = (fn (Periodo ...$periodos) => $periodos)(...$periodos);

?>

<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de períodos
  </h3>
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
    <!-- <li>
      <a href="./usuarios/buscar">
        <i class="fas fa-search fa-fw"></i>
        &nbsp; Buscar usuario
      </a>
    </li> -->
  </ul>
</div>

<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-dark table-sm">
      <thead>
        <tr class="text-center roboto-medium">
          <th>ID</th>
          <th>Año de inicio</th>
          <th>Fecha de registro</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($periodos as $periodo) : ?>
          <tr class="text-center">
            <td><?= $periodo->id ?></td>
            <td><?= $periodo->inicio ?></td>
            <td><?= $periodo->fechaRegistro() ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
