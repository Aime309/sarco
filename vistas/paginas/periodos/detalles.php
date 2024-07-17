<?php

use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Sala;

/**
 * @var array{
 *   salasAsignadas: Sala[],
 *   salas: Sala[],
 *   nuevosEstudiantes: Estudiante[]
 * } $detalles
 */

assert($periodo instanceof Periodo);

$idsSalasAsignadas = array_map(fn (Sala $sala) => $sala->id, $detalles['salasAsignadas']);

?>

<div class="full-box page-header pb-0">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i>
    Período <?= $periodo ?>
  </h1>
  <div class="text-right">
    <button class="btn btn-danger" onclick="imprimir()">
      <i class="fas fa-print"></i>
      Imprimir
    </button>
  </div>
</div>

<section class="container">
  <div class="row mx-0 mb-4">
    <article class="col-md-4 card">
      <h3 class="card-header">1er momento</h3>
      <ul class="list-group">
        <li class="list-group-item">
          <strong>Inicio:</strong> <?= $periodo->momento(1)->inicio('d/m/Y') ?>
        </li>
        <li class="list-group-item">
          <strong>Cierre:</strong> <?= $periodo->momento(1)->cierre('d/m/Y') ?>
        </li>
      </ul>
    </article>
    <article class="col-md-4 card">
      <h3 class="card-header">2do momento</h3>
      <ul class="list-group">
        <li class="list-group-item">
          <strong>Inicio:</strong> <?= $periodo->momento(2)->inicio('d/m/Y') ?>
        </li>
        <li class="list-group-item">
          <strong>Cierre:</strong> <?= $periodo->momento(2)->cierre('d/m/Y') ?>
        </li>
      </ul>
    </article>
    <article class="col-md-4 card">
      <h3 class="card-header">3er momento</h3>
      <ul class="list-group">
        <li class="list-group-item">
          <strong>Inicio:</strong> <?= $periodo->momento(3)->inicio('d/m/Y') ?>
        </li>
        <li class="list-group-item">
          <strong>Cierre:</strong> <?= $periodo->momento(3)->cierre('d/m/Y') ?>
        </li>
      </ul>
    </article>
  </div>
</section>

<section class="full-box tile-container">
  <article class="tile">
    <div class="tile-tittle">Inscripciones</div>
    <div class="tile-icon">
      <i class="fas fa-graduation-cap fa-fw"></i>
      <p>
        <?= $cantidadDeInscripciones === 1
          ? '1 inscripción'
          : "$cantidadDeInscripciones inscripciones"
        ?>
      </p>
    </div>
  </article>
</section>

<h2 class="container">Estado de salas</h2>

<div class="container row mx-auto mb-5">
  <ul class="d-md-none nav nav-pills mb-3 card card-body">
    <?php foreach ($detalles['salas'] as $sala) : ?>
      <?php if (in_array($sala->id, $idsSalasAsignadas)) : ?>
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#sala-<?= $sala ?>">
            Sala <?= $sala ?>
          </a>
        </li>
      <?php else : ?>
        <li class="nav-item" data-bs-toggle="tooltip" title="Esta sala no ha sido asignada">
          <span class="nav-link disabled">
            Sala <?= $sala ?>
          </span>
        </li>
      <?php endif ?>
    <?php endforeach ?>
  </ul>
  <div class="d-none d-md-flex col-md-3 card card-body">
    <div class="nav flex-column nav-pills">
      <?php foreach ($detalles['salas'] as $sala) : ?>
        <?php if (in_array($sala->id, $idsSalasAsignadas)) : ?>
          <a class="nav-link" data-toggle="pill" href="#sala-<?= $sala ?>">
            Sala <?= $sala ?>
          </a>
        <?php else : ?>
          <span class="nav-link" data-bs-toggle="tooltip" title="Esta sala no ha sido asignada">
            Sala <?= $sala ?>
          </span>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>
  <div class="col-md-9 card card-body">
    <div class="tab-content">
      <?php foreach ($detalles['salasAsignadas'] as $sala) : ?>
        <div class="tab-pane fade" id="sala-<?= $sala ?>">
          <h2 class="card-header">Asignaciones a Sala <?= $sala ?></h2>
          <article class="card">
            <h3 class="card-header"><?= $sala->aula ?></h3>
            <footer class="card-footer">
              <ul class="list-group">
                <li class="list-group-item">
                  <strong>Tipo:</strong> <?= $sala->aula->tipo ?>
                </li>
                <li class="list-group-item">
                  <strong>Capacidad:</strong>
                  <?= $sala->aula->capacidad() ?> niños
                </li>
              </ul>
            </footer>
          </article>
          <h3 class="mt-4">Docentes asignados:</h3>
          <div class="row">
            <?php foreach ($sala->docentes() as $docente) : ?>
              <a href="./maestros/<?= $docente->cedula ?>" target="_blank" class="col-md-4">
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
</div>

<script src="./recursos/libs/chart/ResizeObserver.global.js"></script>
<script src="./recursos/libs/chart/chart.js"></script>

<script>
  function imprimir() {
    if (!document.querySelector('.nav-lateral').classList.contains('active')) {
      document.querySelector('.show-nav-lateral').click()
    }

    print()
  }
</script>
