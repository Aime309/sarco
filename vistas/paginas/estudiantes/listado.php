<?php

use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Usuario;

assert($usuario instanceof Usuario);

$estudiantes = (fn (Estudiante ...$estudiantes) => $estudiantes)(...$estudiantes);

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de estudiantes
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <?php if ($usuario->esSecretario()) : ?>
      <li class="mx-0">
        <a href="./estudiantes/inscribir">
          <i class="fas fa-plus fa-fw"></i>
          &nbsp; Inscribir estudiante
        </a>
      </li>
    <?php endif ?>
    <li class="mx-0">
      <a class="active" href="./estudiantes">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de estudiante
      </a>
    </li>
    <li class="mx-0">
      <a href="#buscar-estudiante" data-toggle="modal">
        <i class="fas fa-search fa-fw"></i>
        &nbsp; Buscar estudiante
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <input class="form-control mb-3" placeholder="Buscar estudiante..." oninput="w3.filterHTML('#estudiantes', '.estudiante', this.value)">

<div class="table-responsive">
    <table class="table table-dark table-sm" id="estudiantes">
      <thead>
        <th></th>
         <th onclick="w3.sortHTML('#estudiantes', '.estudiante', 'td:nth-child(2)')">
            Cédula
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#estudiantes', '.estudiante', 'td:nth-child(3)')">
            Nombre completo
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#estudiantes', '.estudiante', 'td:nth-child(4)')">
            Edad
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#estudiantes', '.estudiante', 'td:nth-child(5)')">
            Lugar de Nacimiento
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#estudiantes', '.estudiante', 'td:nth-child(6)')">
            Genéro
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#estudiantes', '.estudiante', 'td:nth-child(7)')">
            Grupo Sanguinio
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#estudiantes', '.estudiante', 'td:nth-child(8)')">
            Fecha de Registro
            <i class="fa fa-sort"></i>
          </th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($estudiantes as $estudiante) : ?>
          <tr class="text-center estudiante">
            <td>
              <a href="./estudiantes/<?= $estudiante->cedula ?>" class="btn btn-secondary">
                Detalles
              </a>
            </td>
            <td><?= $estudiante->cedula ?></td>
            <td><?= $estudiante->nombreCompleto() ?></td>
            <td><?= $estudiante->edad() ?></td>
            <td><?= $estudiante->lugarNacimiento ?></td>
            <td><?= $estudiante->genero ?></td>
            <td><?= $estudiante->grupoSanguineo ?></td>
            <td><?= $estudiante->fechaRegistro() ?></td>
            <td>
              <a href="./estudiantes/<?= $estudiante->cedula ?>" class="btn btn-success">
                Editar
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
