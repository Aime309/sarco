<?php

assert(is_int($cantidadDeUsuarios));
assert(is_int($cantidadDeRepresentantes));
assert(is_int($cantidadDeMaestros));

$contadores = [
  ['href' => './usuarios', 'title' => 'Usuarios', 'icon' => '<i class="fas fa-users fa-fw"></i>', 'footer' => "$cantidadDeUsuarios registrado" . ($cantidadDeUsuarios > 1 ? 's' : '')],
  ['href' => './asignar', 'title' => 'Asignar sala', 'icon' => '<i class="fas fa-pen-to-square fa-fw"></i>'],
  ['href' => './estudiantes', 'title' => 'Estudiantes', 'icon' => '<i class="fas fa-graduation-cap fa-fw"></i>'],
  ['href' => './representantes', 'title' => 'Representantes', 'icon' => '<i class="fas fa-people-roof fa-fw"></i>', 'footer' => "$cantidadDeRepresentantes registrado" . ($cantidadDeRepresentantes > 1 ? 's' : '')],
  ['href' => './maestros', 'title' => 'Maestros', 'icon' => '<i class="fas fa-person-chalkboard fa-fw"></i>', 'footer' => "$cantidadDeMaestros registrado" . ($cantidadDeMaestros > 1 ? 's' : '')],
  ['href' => './periodos', 'title' => 'Períodos', 'icon' => '<i class="fas fa-calendar fa-fw"></i>'],
  ['href' => './momentos', 'title' => 'Momentos', 'icon' => '<i class="fas fa-calendar-days fa-fw"></i>'],
  ['href' => './salas', 'title' => 'Salas', 'icon' => '<i class="fas fa-school-flag fa-fw"></i>'],
];

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Panel de administración
  </h1>
  <p class="text-justify"></p>
</header>

<section class="full-box tile-container">
  <?php foreach ($contadores as $contador): ?>
    <a href="<?= $contador['href'] ?>" class="tile">
      <div class="tile-tittle"><?= $contador['title'] ?></div>
      <div class="tile-icon">
        <?= $contador['icon'] ?>
        <p><?= $contador['footer'] ?? '&nbsp;' ?></p>
      </div>
    </a>
  <?php endforeach ?>
</section>
