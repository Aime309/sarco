<?php

use SARCO\Modelos\Rol;
use SARCO\Modelos\Usuario;

/** @var Usuario $usuario */

?>

<section class="full-box nav-lateral">
  <div class="full-box nav-lateral-bg show-nav-lateral"></div>
  <div class="full-box nav-lateral-content">
    <figure class="full-box nav-lateral-avatar">
      <i class="far fa-times-circle show-nav-lateral"></i>
      <img src="assets/images/favicon.jpg" class="img-fluid rounded-circle" />
      <figcaption class="roboto-medium text-center d-flex flex-column">
        <?= $usuario->nombreCompleto() ?>
        <small class="roboto-condensed-light"><?= $usuario->rol->name ?></small>
      </figcaption>
    </figure>
    <div class="full-box nav-lateral-bar"></div>
    <nav class="full-box nav-lateral-menu">
      <ul>
        <li>
          <a href="./">
            <i class="fab fa-dashcube fa-fw"></i>
            Inicio
          </a>
        </li>
        <?php if ($usuario->rol === Rol::Director) : ?>
          <li>
            <a href="#" class="nav-btn-submenu">
              <i class="fas  fa-user-secret fa-fw"></i>
              Usuarios
              <i class="fas fa-chevron-down"></i>
            </a>
            <ul>
              <li>
                <a href="user-new/">
                  <i class="fas fa-plus fa-fw"></i>
                  Nuevo usuario
                </a>
              </li>
              <li>
                <a href="user-list/">
                  <i class="fas fa-clipboard-list fa-fw"></i>
                  Lista de usuarios
                </a>
              </li>
              <li>
                <a href="user-search/">
                  <i class="fas fa-search fa-fw"></i>
                  Buscar usuario
                </a>
              </li>
            </ul>
          </li>
        <?php endif ?>
      </ul>
    </nav>
  </div>
</section>
