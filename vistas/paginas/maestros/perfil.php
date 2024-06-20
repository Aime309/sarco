<?php

use Jenssegers\Date\Date;
use SARCO\Modelos\Inscripcion;
use SARCO\Modelos\Maestro;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Persona;

/**
 * @var array<string, array{
 *   sala: Sala,
 *   aula: Aula,
 *   compañeros: Maestro[],
 *   inscripciones: Inscripcion[]
 * }> $informacionLaboral
 */

assert($maestro instanceof Maestro);

$periodoSeleccionado = (string) max(array_keys($informacionLaboral));

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i>
    <?= $maestro->nombreCompleto() ?>
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
      <a class="nav-link" data-toggle="pill" href="#estado-laboral">
        Estado laboral
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="pill" href="#estudiantes">
        Estudiantes
      </a>
    </li>
  </ul>
  <div class="d-none d-md-flex col-md-3 card card-body">
    <div class="nav flex-column nav-pills">
      <a class="nav-link active" data-toggle="pill" href="#info-personal">
        Información personal
      </a>
      <a class="nav-link" data-toggle="pill" href="#estado-laboral">
        Estado laboral
      </a>
      <a class="nav-link" data-toggle="pill" href="#estudiantes">
        Estudiantes
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
              <td><?= $maestro->nombreCompleto() ?></td>
            </tr>
            <tr>
              <th>Cédula:</th>
              <td><?= $maestro->cedula ?></td>
            </tr>
            <tr>
              <th>Fecha de nacimiento:</th>
              <td>
                <?= Date::createFromFormat('Y-m-d', $maestro->fechaNacimiento)->format('d \d\e M \d\e\l Y') ?>
              </td>
            </tr>
            <tr>
              <th>Edad:</th>
              <td>
                <?= $maestro->edad() . ($maestro->edad() === 1 ? ' año' : ' años') ?>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="estado-laboral">
        <h2 class="card-header">Estado laboral</h2>
        <div class="dropdown d-inline-block">
          <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
            Visualizar período
          </button>
          <div class="dropdown-menu">
            <?php foreach (array_keys($informacionLaboral) as $periodo) : ?>
              <a class="dropdown-item" data-toggle="tab" onclick="cambiarPeriodo(`<?= $periodo ?>`)" href="#estado-laboral-<?= $periodo ?>">
                <?= $periodo ?>
              </a>
            <?php endforeach ?>
          </div>
        </div>
        <button class="btn text-dark" disabled>
          <?= $periodoSeleccionado ?>
        </button>
        <div class="tab-content">
          <?php foreach ($informacionLaboral as $periodo => $informacion) : ?>
            <div class="tab-pane fade <?= $periodo === $periodoSeleccionado ? 'show active' : '' ?>" id="estado-laboral-<?= $periodo ?>">
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
              <h3>Compañeros de sala/aula:</h3>
              <div class="row">
                <?php foreach ($informacion['compañeros'] as $docente) : ?>
                  <div class="col-md">
                    <a href="./maestros/<?= $docente->cedula ?>" target="_blank" class="card pt-2">
                      <img src="./node_modules/@fortawesome/fontawesome-free/svgs/solid/user.svg" class="card-img-top w-25 mx-auto" />
                      <h4 class="card-header h6 text-center">
                        <?= $docente->nombreCompleto() ?>
                      </h4>
                      <ul class="list-group">
                        <li class="list-group-item">v-<?= $docente->cedula ?></li>
                        <li class="list-group-item"><?= $docente->genero ?></li>
                      </ul>
                    </a>
                  </div>
                <?php endforeach ?>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
      <div class="tab-pane fade" id="estudiantes">
        <h2 class="table-responsive">Estudiantes</h2>
        <div class="dropdown d-inline-block">
          <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
            Visualizar período
          </button>
          <div class="dropdown-menu">
            <?php foreach (array_keys($informacionLaboral) as $periodo) : ?>
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
          <?php foreach ($informacionLaboral as $periodo => $informacion) : ?>
            <div class="tab-pane fade <?= $periodo === $periodoSeleccionado ? 'show active' : '' ?>" id="estudiantes-<?= $periodo ?>">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <tr>
                    <th>Nombre completo</th>
                    <th>Cédula escolar</th>
                    <th>Edad</th>
                  </tr>
                  <?php foreach ($informacion['inscripciones'] as $inscripcion) : ?>
                    <tr>
                      <td><?= $inscripcion->estudiante() ?></td>
                      <td><?= $inscripcion->cedulaEstudiante ?></td>
                      <td><?= Persona::calcularEdad($inscripcion->fechaNacimientoEstudiante) ?></td>
                    </tr>
                  <?php endforeach ?>
                </table>
              </div>
            </div>
          <?php endforeach ?>
      </div>
    </div>
  </div>
</div>

<script>
  const $inputsPeriodoEstadoLaboral = document.querySelectorAll('button[disabled]')

  function imprimir() {
    if (!document.querySelector('.nav-lateral').classList.contains('active')) {
      document.querySelector('.show-nav-lateral').click()
    }

    print()
  }

  function cambiarPeriodo(periodo) {
    $inputsPeriodoEstadoLaboral.forEach($inputPeriodoEstadoLaboral => {
      $inputPeriodoEstadoLaboral.textContent = periodo
    })
  }
</script>
