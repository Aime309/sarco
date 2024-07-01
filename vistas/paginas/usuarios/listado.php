<?php

use SARCO\Modelos\Usuario;

assert($usuario instanceof Usuario);

$usuarios = (fn (Usuario ...$usuarios) => $usuarios)(...$usuarios);
$usuarioAutenticado = $usuario;

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de usuarios
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <?php if (!$usuarioAutenticado->esDocente()) : ?>
      <li>
        <a href="./usuarios/nuevo">
          <i class="fas fa-plus fa-fw"></i>
          &nbsp; Nuevo usuario
        </a>
      </li>
    <?php endif ?>
    <li>
      <a class="active" href="./usuarios/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de usuarios
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
          <th>Dirección</th>
          <th>Rol</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $usuarioIterado) : ?>
          <tr class="text-center <?= !$usuarioIterado->estaActivo ? 'bg-light' : '' ?>">
            <td><?= $usuarioIterado->cedula ?></td>
            <td><?= $usuarioIterado->nombreCompleto() ?></td>
            <td><?= $usuarioIterado->edad() ?></td>
            <td><?= $usuarioIterado->telefono ?></td>
            <td><?= $usuarioIterado->correo ?></td>
            <td><?= $usuarioIterado->direccion ?></td>
            <td><?= $usuarioIterado->rol ?></td>
            <td>
              <?php if ($usuarioAutenticado->esDirector()) : ?>
                <?php if ($usuarioIterado->estaActivo) : ?>
                  <a href="./usuarios/<?= $usuarioIterado->cedula ?>/desactivar" class="btn btn-danger">
                    Desactivar
                  </a>
                <?php else : ?>
                  <a href="./usuarios/<?= $usuarioIterado->cedula ?>/activar" class="btn btn-success">
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
