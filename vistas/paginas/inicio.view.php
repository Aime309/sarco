<?php

use Leaf\BareUI;

/** @var BareUI $template */

$contadores = [
  ['href' => '#', 'title' => 'Usuarios', 'icon' => '<i class="fas fa-users fa-fw"></i>', 'footer' => "$cantidadDeUsuarios registrado" . ($cantidadDeUsuarios > 1 ? 's' : '')],
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
        <p><?= $contador['footer'] ?></p>
      </div>
    </a>
  <?php endforeach ?>
</section>
