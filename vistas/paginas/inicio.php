<?php

use SARCO\Modelos\Momento;
use SARCO\Modelos\Periodo;

assert(is_int($cantidadDeUsuarios));
assert(is_int($cantidadDeRepresentantes));
assert(is_int($cantidadDeMaestros));
assert($ultimoPeriodo instanceof Periodo || $ultimoPeriodo === null);
assert($ultimoMomento instanceof Momento || $ultimoMomento === null);

$contadores = [
  ['href' => './usuarios', 'title' => 'Usuarios', 'icon' => '<i class="fas fa-users fa-fw"></i>', 'footer' => "$cantidadDeUsuarios registrado" . ($cantidadDeUsuarios > 1 ? 's' : '')],
  ['href' => './asignar', 'title' => 'Asignar sala', 'icon' => '<i class="fas fa-pen-to-square fa-fw"></i>'],
  ['href' => './estudiantes', 'title' => 'Estudiantes', 'icon' => '<i class="fas fa-graduation-cap fa-fw"></i>'],
  ['href' => './representantes', 'title' => 'Representantes', 'icon' => '<i class="fas fa-people-roof fa-fw"></i>', 'footer' => "$cantidadDeRepresentantes registrado" . ($cantidadDeRepresentantes > 1 ? 's' : '')],
  ['href' => './maestros', 'title' => 'Maestros', 'icon' => '<i class="fas fa-person-chalkboard fa-fw"></i>', 'footer' => "$cantidadDeMaestros registrado" . ($cantidadDeMaestros > 1 ? 's' : '')],
  ['href' => './salas', 'title' => 'Salas', 'icon' => '<i class="fas fa-school-flag fa-fw"></i>'],
];

?>

<header class="full-box page-header">
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
        <small class="h4"><?= $ultimoMomento ?></small>
      </div>
    <?php else : ?>
      <a href="./periodos/nuevo" class="col" style="font-size: .65em">
        Aperturar período
      </a>
    <?php endif ?>
  </h1>
  <p class="text-justify"></p>
</header>

<section class="full-box tile-container">
  <?php foreach ($contadores as $contador) : ?>
    <a href="<?= $contador['href'] ?>" class="tile">
      <div class="tile-tittle"><?= $contador['title'] ?></div>
      <div class="tile-icon">
        <?= $contador['icon'] ?>
        <p><?= $contador['footer'] ?? '&nbsp;' ?></p>
      </div>
    </a>
  <?php endforeach ?>
</section>
