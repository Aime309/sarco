<?php

use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Maestro;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Sala;

/**
 * @var array<string, array{
 *   aula: Aula,
 *   docentes: Maestro[],
 *   estudiantes: Estudiante[]
 * }> $detalles
 */

assert($periodoActual === null || $periodoActual instanceof Periodo);
assert($sala instanceof Sala);

$periodoSeleccionado = (string) max(array_keys($detalles ?: [
  $periodoActual->__toString() => []
]));

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i>
    Sala <?= $sala ?>
  </h1>
  <div class="text-right">
    <button class="btn btn-danger" onclick="imprimir()">
      <i class="fas fa-print"></i>
      Imprimir
    </button>
  </div>
</header>

<div class="container row mx-auto mb-5">
  <div class="d-none d-md-flex col-md-3 card card-body">
    <div class="nav flex-column nav-pills">
      <a class="nav-link active" data-toggle="pill" href="#info-general">
        Información general
      </a>
      <a class="nav-link" data-toggle="pill" href="#asignaciones">
        Asignaciones
      </a>
      <a class="nav-link" data-toggle="pill" href="#estudiantes">
        Estudiantes
      </a>
    </div>
  </div>
  <div class="col-md-9 card card-body">
    <div class="tab-content">
      <div class="tab-pane fade show active" id="info-general">
        <h2 class="card-header">Información general</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <tr>
              <th>Nombre:</th>
              <td>Sala <?= $sala ?></td>
            </tr>
            <tr>
              <th>Edad mínima:</th>
              <td><?= $sala->edadMinima ?></td>
            </tr>
            <tr>
              <th>Edad máxima:</th>
              <td>
                <?= $sala->edadMaxima ?>
              </td>
            </tr>
            <tr>
              <th>Fecha de apertura:</th>
              <td>
                <?= $sala->fechaRegistro() ?>
              </td>
            </tr>
            <tr>
              <th>Estado:</th>
              <td>
                <?php if ($sala->estaActiva) : ?>
                  <span class="badge badge-success">Activa</span>
                <?php else : ?>
                  <span class="badge badge-danger">Inactiva</span>
                <?php endif ?>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="asignaciones">
        <h2 class="card-header">Asignaciones</h2>
        <div class="dropdown d-inline-block">
          <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
            Visualizar período
          </button>
          <div class="dropdown-menu">
            <?php foreach (array_keys($detalles) as $periodo) : ?>
              <a class="dropdown-item" data-toggle="tab" onclick="cambiarPeriodo(`<?= $periodo ?>`)" href="#asignaciones-<?= $periodo ?>">
                <?= $periodo ?>
              </a>
            <?php endforeach ?>
          </div>
        </div>
        <button class="btn text-dark" disabled>
          <?= $periodoSeleccionado ?>
        </button>
        <div class="tab-content">
          <?php foreach ($detalles as $periodo => $asignacion) : ?>
            <div class="tab-pane fade <?= $periodo === $periodoSeleccionado ? 'show active' : '' ?>" id="asignaciones-<?= $periodo ?>">
              <article class="card">
                <h3 class="card-header"><?= $asignacion['aula'] ?></h3>
                <footer class="card-footer">
                  <ul class="list-group">
                    <li class="list-group-item">
                      <strong>Tipo:</strong> <?= $asignacion['aula']->tipo ?>
                    </li>
                    <li class="list-group-item">
                      <strong>Capacidad:</strong>
                      <?= $asignacion['aula']->capacidad() ?> niños
                    </li>
                  </ul>
                </footer>
              </article>
              <h3 class="mt-4">Docentes asignados:</h3>
              <div class="row">
                <?php foreach ($asignacion['docentes'] as $docente) : ?>
                  <a href="./maestros/<?= $docente->cedula ?>" target="_blank" class="col-md">
                    <article class="card pt-2">
                      <img src="./node_modules/@fortawesome/fontawesome-free/svgs/solid/user.svg" class="card-img-top w-25 mx-auto" />
                      <h4 class="card-header h6 text-center">
                        <?= $docente->nombreCompleto() ?>
                      </h4>
                      <ul class="list-group">
                        <li class="list-group-item">v-<?= $docente->cedula ?></li>
                        <li class="list-group-item"><?= $docente->genero ?></li>
                      </ul>
                    </article>
                  </a>
                <?php endforeach ?>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
      <div class="tab-pane fade" id="estudiantes">
        <h2 class="card-header">Estudiantes</h2>
        <div class="dropdown d-inline-block">
          <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
            Visualizar período
          </button>
          <div class="dropdown-menu">
            <?php foreach (array_keys($detalles) as $periodo) : ?>
              <a class="dropdown-item" data-toggle="tab" onclick="cambiarPeriodo(`<?= $periodo ?>`)" href="#estudiantes-<?= $periodo ?>">
                <?= $periodo ?>
              </a>
            <?php endforeach ?>
          </div>
        </div>
        <button class="btn text-dark" disabled>
          <?= $periodoSeleccionado ?>
        </button>
        <div class="tab-content">
          <?php foreach ($detalles as $periodo => $asignacion) : ?>
            <div class="tab-pane fade <?= $periodo === $periodoSeleccionado ? 'show active' : '' ?>" id="estudiantes-<?= $periodo ?>">
              <div class="row">
                <?php foreach ($asignacion['estudiantes'] as $estudiante) : ?>
                  <div class="col-md-4">
                    <div class="card">
                      <img class="px-5" src="./node_modules/@fortawesome/fontawesome-free/svgs/solid/user.svg">
                      <div class="card-body">
                        <h4><?= $estudiante ?></h4>
                      </div>
                    </div>
                  </div>
                <?php endforeach ?>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>
</div>
