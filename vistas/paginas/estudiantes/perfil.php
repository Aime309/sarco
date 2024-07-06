<?php

use flight\template\View;
use Jenssegers\Date\Date;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Boletin;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Usuario;

/**
 * @var array<string, array{
 *   boletines: Boletin[],
 *   sala: Sala,
 *   aula: Aula,
 *   docentes: Usuario[]
 * }> $informacionAcademica
 *
 * @var Estudiante $estudiante
*/

assert($vistas instanceof View);

$periodoSeleccionado = (string) max(array_keys($informacionAcademica));

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i>
    <?= $estudiante->nombreCompleto() ?>
  </h1>
  <div class="text-right">
    <button class="btn btn-danger" onclick="imprimir()">
      <i class="fas fa-print"></i>
      Imprimir
    </button>
  </div>
</div>

<div class="container row mx-auto mb-5">
  <ul class="d-md-none nav nav-pills mb-3 card card-body">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="pill" href="#info-personal">
        Información personal
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="pill" href="#info-academica">
        Información académica
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="pill" href="#representantes">
        Representantes
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="pill" href="#boletines">
        Boletines
      </a>
    </li>
  </ul>
  <div class="d-none d-md-flex col-md-3 card card-body">
    <div class="nav flex-column nav-pills">
      <a class="nav-link active" data-toggle="pill" href="#info-personal">
        Información personal
      </a>
      <a class="nav-link" data-toggle="pill" href="#info-academica">
        Información académica
      </a>
      <a class="nav-link" data-toggle="pill" href="#representantes">
        Representantes
      </a>
      <a class="nav-link" data-toggle="pill" href="#boletines">
        Boletines
      </a>
    </div>
  </div>
  <div class="col-md-9 card card-body">
    <div class="tab-content">
      <div class="tab-pane fade show active" id="info-personal">
        <h2 class="card-header">Información personal</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <tr>
              <th>Nombre completo:</th>
              <td><?= $estudiante->nombreCompleto() ?></td>
            </tr>
            <tr>
              <th>Cédula escolar:</th>
              <td><?= $estudiante->cedula ?></td>
            </tr>
            <tr>
              <th>Lugar de nacimiento:</th>
              <td>
                <?= $estudiante->lugarNacimiento ?>
              </td>
            </tr>
            <tr>
              <th>Fecha de nacimiento:</th>
              <td>
                <?= Date::createFromFormat('Y-m-d', $estudiante->fechaNacimiento)->format('d \d\e M \d\e\l Y') ?>
              </td>
            </tr>
            <tr>
              <th>Edad:</th>
              <td>
                <?= $estudiante->edad() . ($estudiante->edad() === 1 ? ' año' : ' años') ?>
              </td>
            </tr>
            <tr>
              <th>Género:</th>
              <td>
                <?= $estudiante->genero ?>
              </td>
            </tr>
            <tr>
              <th>Tipo de sangre:</th>
              <td>
                <?= $estudiante->grupoSanguineo ?>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="info-academica">
        <h2 class="card-header">Información académica</h2>
        <div class="dropdown d-inline-block">
          <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
            Visualizar período
          </button>
          <div class="dropdown-menu">
            <?php foreach (array_keys($informacionAcademica) as $periodo) : ?>
              <a class="dropdown-item" data-toggle="tab" onclick="cambiarPeriodo(`<?= $periodo ?>`)" href="#info-academica-<?= $periodo ?>">
                <?= $periodo ?>
              </a>
            <?php endforeach ?>
          </div>
        </div>
        <button class="btn text-dark" disabled>
          <?= $periodoSeleccionado ?>
        </button>
        <div class="tab-content">
          <?php foreach ($informacionAcademica as $periodo => $informacion) : ?>
            <div class="tab-pane fade <?= $periodo === $periodoSeleccionado ? 'show active' : '' ?>" id="info-academica-<?= $periodo ?>">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <tr>
                    <th>Sala asignada:</th>
                    <td><?= $informacion['sala'] ?></td>
                  </tr>
                  <tr>
                    <th>Aula asignada:</th>
                    <td><?= $informacion['aula'] ?></td>
                  </tr>
                </table>
              </div>
              <h3>Docentes asignados:</h3>
              <div class="row">
                <?php foreach ($informacion['docentes'] as $docente) : ?>
                  <a href="./maestros/<?= $docente->cedula ?>" target="_blank" class="col-md">
                    <article class="card pt-2">
                      <img src="./node_modules/@fortawesome/fontawesome-free/svgs/solid/user.svg" class="card-img-top w-25 mx-auto" />
                      <h4 class="card-header h6 text-center">
                        <?= $docente->nombreCompleto() ?>
                      </h4>
                      <ul class="list-group">
                        <li class="list-group-item">
                          <?php if ($docente->estaActivo) : ?>
                            <span class="badge badge-success">Activo</span>
                          <?php else : ?>
                            <span class="badge badge-danger">Inactivo</span>
                          <?php endif ?>
                        </li>
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
      <div class="tab-pane fade" id="representantes">
        <h2 class="card-header">Representantes</h2>
        <div class="row">
          <?php foreach ($estudiante->representantes() as $representante) : ?>
            <a href="./representantes/<?= $representante->cedula ?>" target="_blank" class="col-md-6 card pt-2">
              <img src="./node_modules/@fortawesome/fontawesome-free/svgs/solid/user.svg" class="card-img-top w-25 mx-auto" />
              <h4 class="card-header h6 text-center">
                <?= $representante->nombreCompleto() ?>
              </h4>
              <ul class="list-group">
                <li class="list-group-item">v-<?= $representante->cedula ?></li>
                <li class="list-group-item"><?= $representante->genero ?></li>
              </ul>
            </a>
          <?php endforeach ?>
        </div>
      </div>
      <div class="tab-pane fade" id="boletines">
        <h2 class="card-header">Boletines</h2>
        <div class="dropdown d-inline-block">
          <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
            Visualizar período
          </button>
          <div class="dropdown-menu">
            <?php foreach (array_keys($informacionAcademica) as $periodo) : ?>
              <a class="dropdown-item" data-toggle="tab" onclick="cambiarPeriodo(`<?= $periodo ?>`)" href="#info-academica-boletines-<?= $periodo ?>">
                <?= $periodo ?>
              </a>
            <?php endforeach ?>
          </div>
        </div>
        <button class="btn text-dark" disabled>
          <?= $periodoSeleccionado ?>
        </button>
        <div class="tab-content">
          <?php foreach ($informacionAcademica as $periodo => $informacion) : ?>
            <div class="tab-pane fade <?= $periodo === $periodoSeleccionado ? 'show active' : '' ?>" id="info-academica-boletines<?= $periodo ?>">
              <?php foreach ($informacion['boletines'] as $boletin) : ?>
                <div class="table-responsive">
                  <table class="table table-striped table-sm">
                    <caption class="h3 d-flex justify-content-between flex-col flex-md-row" style="caption-side: top">
                      <span>Momento <?= $boletin->momento ?></span>
                      <a href="./estudiantes/boletines/<?= $boletin->id ?>/editar" class="btn btn-success">
                        Editar
                      </a>
                    </caption>
                    <tr>
                      <th>Inasistencias:</th>
                      <td><?= $boletin->inasistencias ?></td>
                    </tr>
                    <tr>
                      <th>Proyecto:</th>
                      <td><?= $boletin->proyecto ?></td>
                    </tr>
                    <tr>
                      <th>Descripción de formación:</th>
                      <td><?= $boletin->descripcionFormacion ?></td>
                    </tr>
                    <tr>
                      <th>Descripción de ambiente:</th>
                      <td><?= $boletin->descripcionAmbiente ?></td>
                    </tr>
                    <tr>
                      <th>Recomendaciones:</th>
                      <td><?= $boletin->recomendaciones ?></td>
                    </tr>
                  </table>
                </div>
              <?php endforeach ?>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const $inputsPeriodoInfoAcademica = document.querySelectorAll('#info-academica button[disabled]')

  function imprimir() {
    if (!document.querySelector('.nav-lateral').classList.contains('active')) {
      document.querySelector('.show-nav-lateral').click()
    }

    print()
  }

  function cambiarPeriodo(periodo) {
    $inputsPeriodoInfoAcademica.forEach($inputPeriodoInfoAcademica => {
      $inputPeriodoInfoAcademica.textContent = periodo
    })
  }
</script>
