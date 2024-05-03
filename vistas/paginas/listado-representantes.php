<?php

use SARCO\Modelos\Representante;

/**
 * @var array<int, Representante> $representantes
 */

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
      <a href="./representantes/nuevo"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo representante</a>
    </li>
    <li>
      <a class="active" href="./representantes/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de representantes</a>
    </li>
    <li>
      <a href="./representantes/buscar"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar representante</a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <div class="table-responsive">
    <table class="table table-dark table-sm">
      <thead>
        <tr class="text-center roboto-medium">
          <th>#</th>
          <th>Cédula</th>
          <th>Nombre completo</th>
          <th>Sexo</th>
          <th>Edad</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Dirección</th>
          <th colspan="2"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($representantes as $representante): ?>
          <tr class="text-center">
            <td><?= $representante->id ?></td>
            <td><?= $representante->cedula ?></td>
            <td><?= $representante->nombreCompleto() ?></td>
            <td><?= $representante->sexo->value ?></td>
            <td><?= $representante->obtenerEdad() ?></td>
            <td><?= $representante->telefono ?></td>
            <td><?= $representante->correo ?></td>
            <td><?= $representante->direccion ?></td>
            <td>
              <a href="./representantes/<?= $representante->cedula ?>/editar" class="btn btn-success">
                <i class="fas fa-sync-alt"></i>
              </a>
            </td>
            <td></td>
          </tr>
        <?php endforeach ?>
        <!-- <tr class="text-center" ><td colspan="9">No hay registros en el sistema</td></tr> -->
      </tbody>
    </table>
  </div>
  <p class="text-right">Mostrando representante '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>
</div>
