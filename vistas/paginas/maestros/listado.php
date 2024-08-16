<?php

use SARCO\Modelos\Maestro;
use SARCO\Modelos\Usuario;

/**
 * @var Maestro[] $maestros
 * @var Usuario $usuario
 */

$maestros = (fn (Maestro ...$maestros) => $maestros)(...$maestros);
$usuarioAutenticado = $usuario;

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> Lista de maestros
  </h1>
  <p class="text-justify"></p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs mx-2">
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
    <li>
      <a href="#buscar-maestro" data-toggle="modal">
        <i class="fas fa-search fa-fw"></i>
        &nbsp; Buscar maestro
      </a>
    </li>
  </ul>
</div>

<div class="container-fluid">
  <input class="form-control mb-3" placeholder="Buscar maestro..." oninput="w3.filterHTML('#maestros', '.maestro', this.value)">
  <div class="table-responsive">
    <table class="table table-dark table-sm" id="maestros">
      <thead>
        <tr class="text-center roboto-medium">
          <th onclick="w3.sortHTML('#maestros', '.maestro', 'td:nth-child(2)')">
            <th>
            Cédula
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#maestros', '.maestro', 'td:nth-child(3)')">
            Nombre completo
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#maestros', '.maestro', 'td:nth-child(4)')">
            Edad
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#maestros', '.maestro', 'td:nth-child(5)')">
            Teléfono
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#maestros', '.maestro', 'td:nth-child(6)')">
            Correo
            <i class="fa fa-sort"></i>
          </th>
          <th onclick="w3.sortHTML('#maestros', '.maestro', 'td:nth-child(7)')">
            Dirección
            <i class="fa fa-sort"></i>
          </th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($maestros as $maestroIterado) : ?>
          <tr class="text-center maestro <?= !$maestroIterado->estaActivo ? 'bg-light' : '' ?>">
            <td>
              <a href="./maestros/<?= $maestroIterado->cedula ?>" class="btn btn-secondary">
                Detalles
              </a>
            </td>
            <td><?= $maestroIterado->cedula ?></td>
            <td><?= $maestroIterado->nombreCompleto() ?></td>
            <td><?= $maestroIterado->edad() ?></td>
            <td><?= $maestroIterado->telefono ?></td>
            <td><?= $maestroIterado->correo ?></td>
            <td><?= $maestroIterado->direccion ?></td>
            <td>
              <?php if (!$usuario->esDocente()) : ?>
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
            <td>
              <?php if ($usuarioAutenticado->esDirector() || $usuarioAutenticado->esSecretario()) : ?>
                <a href="./usuarios/<?= $maestroIterado->cedula ?>/restablecer-clave" class="btn btn-secondary">
                  Restablecer contraseña
                </a>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
