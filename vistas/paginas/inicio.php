<?php

use Jenssegers\Date\Date;
use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Usuario;

assert(is_int($cantidadDeUsuarios));
assert(is_int($cantidadDeRepresentantes));
assert(is_int($cantidadDeMaestros));
assert(is_int($cantidadDeSalas));
assert(is_int($cantidadDeEstudiantes));
assert($ultimoPeriodo instanceof Periodo || $ultimoPeriodo === null);
assert($ultimoMomento instanceof Momento || $ultimoMomento === null);
assert($usuario instanceof Usuario);

$contadores = [];

if (!$usuario->esDocente()) {
  $contadores[] = [
    'href' => './usuarios',
    'title' => 'Usuarios',
    'icon' => '<i class="fas fa-users fa-fw"></i>',
    'footer' => "$cantidadDeUsuarios registrado" . ($cantidadDeUsuarios > 1 ? 's' : '')
  ];

  $contadores[] = [
    'href' => './salas',
    'title' => 'Salas',
    'icon' => '<i class="fas fa-school-flag fa-fw"></i>',
    'footer' => "$cantidadDeSalas registrada" . (
      $cantidadDeSalas > 1 ? 's' : ''
    )
  ];
}

$contadores[] = [
  'href' => './estudiantes',
  'title' => 'Estudiantes',
  'icon' => '<i class="fas fa-graduation-cap fa-fw"></i>',
  'footer' => "$cantidadDeEstudiantes inscrito" . (
    $cantidadDeEstudiantes > 1 ? 's' : ''
  )
];

$fechaActual = Date::now()->format('l j \d\e F');

?>

<header class="full-box page-header" style="padding-bottom: 0">
  <h1 class="text-left row text-center">
    <span class="col-md-7 text-left">
      <i class="fab fa-dashcube fa-fw"></i>
      Panel de administración
    </span>
    <?php if ($ultimoPeriodo) : ?>
      <div class="col">
        <a href="./periodos" class="fw-bold h4">
          <?= $ultimoPeriodo ?>
        </a>
        <span class="h4"> ~ </span>
        <small class="h4">
          <?= $ultimoMomento ?>
          <br />
          (<?= $fechaActual ?>)
        </small>
      </div>
    <?php elseif ($usuario->esDirector()) : ?>
      <a href="./periodos/nuevo" class="col" style="font-size: .65em">
        Aperturar período
        <br />
        (<?= $fechaActual ?>)
      </a>
    <?php else : ?>
      <strong class="col" style="font-size: .65em">
        Período no aperturado
        <br />
        (<?= $fechaActual ?>)
      </strong>
    <?php endif ?>
  </h1>
  <p class="text-justify"></p>
</header>

<img src="recursos/imagenes/fondo-niños.png" style="width: 100%" />
