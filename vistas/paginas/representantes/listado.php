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
   <input class="form-control mb-3" placeholder="Buscar representantes..." oninput="w3.filterHTML('#representantes', '.representante', this.value)">
  <div class="table-responsive">
    <table class="table table-dark table-sm" id="representantes">
      <thead>
        <th></th>
         <th onclick="w3.sortHTML('#representantes', '.representante', 'td:nth-child(2)')">
           Cédula
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#representantes', '.representante', 'td:nth-child(3)')">
            Nombre completo
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#representantes', '.representante', 'td:nth-child(4)')">
            Edad
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#representantes', '.representante', 'td:nth-child(5)')">
            Teléfono
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#representantes', '.representante', 'td:nth-child(6)')">
           Correo
            <i class="fa fa-sort"></i>
          </th>
          <th></th>
        </tr>
       <tbody>
        <?php foreach ($representantes as $representante) : ?>
          <tr class="text-center representante">
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
