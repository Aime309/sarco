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
  <div class="table-responsive">
    <table class="table table-dark table-sm">
      <thead>
        <tr class="text-center roboto-medium">
          <th></th>
          <th>Cédula</th>
          <th>Nombre completo</th>
          <th>Edad</th>
          <th>Lugar de nacimiento</th>
          <th>Género</th>
          <th>Grupo sanguíneo</th>
          <th>Fecha de registro</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($estudiantes as $estudiante) : ?>
          <tr class="text-center">
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
