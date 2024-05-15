<?php

use SARCO\Modelos\Usuario;

assert($usuario instanceof Usuario);

/**
 * @var array{
 *   href: string,
 *   icono: string,
 *   titulo: string,
 *   subenlaces?: array{href: string, icono: string, titulo: string}[]
 * }[]
 */
$enlaces = [
  ['href' => './', 'icono' => '<i class="fab fa-dashcube fa-fw"></i>', 'titulo' => 'Inicio']
];

if (!$usuario->esDocente()) {
  $enlaces[] = ['icono' => '<i class="fas fa-users fa-fw"></i>', 'titulo' => 'Usuarios', 'subenlaces' => [
    ['href' => 'usuarios/nuevo', 'icono' => '<i class="fas fa-plus fa-fw"></i>', 'titulo' => 'Nuevo usuario'],
    ['href' => 'usuarios', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de usuario'],
    // ['href' => 'usuarios/buscar', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Buscar usuario'],
  ]];
}

$enlaces[] = ['icono' => '<i class="fas fa-graduation-cap fa-fw"></i>', 'titulo' => 'Estudiantes', 'subenlaces' => [
  ['href' => 'estudiantes/inscribir', 'icono' => '<i class="fas fa-plus fa-fw"></i>', 'titulo' => 'Inscribir estudiante'],
  ['href' => 'estudiantes', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de estudiantes'],
  ['href' => 'inscripciones', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de inscripciones'],
  // ['href' => 'estudiantes/buscar', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Buscar estudiante'],
  ['href' => 'estudiantes/boletines', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Lista de boletines'],
]];

$enlaces[] = ['icono' => '<i class="fas fa-school-flag fa-fw"></i>', 'titulo' => 'Salas', 'subenlaces' => [
  ['href' => 'salas/nueva', 'icono' => '<i class="fas fa-plus fa-fw"></i>', 'titulo' => 'Registrar Sala'],
  ['href' => 'salas', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de Salas'],
  // ['href' => 'salas/buscar', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Buscar Sala'],
]];

$enlaces[] = ['icono' => '<i class="fas fa-person-chalkboard fa-fw"></i>', 'titulo' => 'Maestros', 'subenlaces' => [
  ['href' => 'usuarios/nuevo?rol=maestro', 'icono' => '<i class="fas fa-plus fa-fw"></i>', 'titulo' => 'Registrar Maestro'],
  ['href' => 'maestros', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de Maestros'],
  // ['href' => 'maestros/buscar', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Buscar Maestro'],
]];

if ($usuario->esDirector()) {
  $enlaces[] = ['icono' => '<i class="fas fa-people-roof fa-fw"></i>', 'titulo' => 'Representantes', 'subenlaces' => [
    ['href' => 'representantes', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de Representante'],
  ]];
} else {
  $enlaces[] = ['icono' => '<i class="fas fa-people-roof fa-fw"></i>', 'titulo' => 'Representantes', 'subenlaces' => [
    ['href' => 'representantes/nuevo', 'icono' => '<i class="fas fa-plus fa-fw"></i>', 'titulo' => 'Registrar Representante'],
    ['href' => 'representantes', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de Representante'],
    // ['href' => 'representantes/buscar', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Buscar Representante'],
  ]];
}

$enlaces[] = ['icono' => '<i class="fas fa-calendar fa-fw"></i>', 'titulo' => 'Periodos', 'subenlaces' => [
  ['href' => 'periodos/nuevo', 'icono' => '<i class="fas fa-plus fa-fw"></i>', 'titulo' => 'Aperturar Período'],
  ['href' => 'periodos', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de Períodos'],
  // ['href' => 'periodos/buscar', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Buscar Periodo'],
]];

$enlaces[] = ['icono' => '<i class="fas fa-calendar-days fa-fw"></i>', 'titulo' => 'Momentos', 'subenlaces' => [
  // ['href' => 'momentos/registrar', 'icono' => '<i class="fas fa-plus fa-fw"></i>', 'titulo' => 'Registrar Momento'],
  ['href' => 'momentos', 'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>', 'titulo' => 'Lista de Momentos'],
  // ['href' => 'momentos/buscar', 'icono' => '<i class="fas fa-search fa-fw"></i>', 'titulo' => 'Buscar Momento'],
]];

if ($usuario->esDirector()) {
  $enlaces[] = ['icono' => '<i class="fas fa-gears fa-fw"></i>', 'titulo' => 'Configuraciones', 'subenlaces' => [
    ['href' => 'respaldar', 'icono' => '<i class="fas fa-floppy-disk fa-fw"></i>', 'titulo' => 'Respaldar sistema'],
    ['href' => 'restaurar', 'icono' => '<i class="fas fa-rotate-left fa-fw"></i>', 'titulo' => 'Restaurar sistema'],
  ]];
}

?>

<aside class="full-box nav-lateral" style="background-image: url(recursos/imagenes/nav-font.jpg)">
  <div class="full-box nav-lateral-bg show-nav-lateral"></div>
  <div class="full-box nav-lateral-content">
    <figure class="full-box nav-lateral-avatar">
      <i class="far fa-times-circle show-nav-lateral"></i>
      <img src="recursos/imagenes/favicon.jpg" class="img-fluid rounded-circle" />
      <figcaption class="roboto-medium text-center d-flex flex-column">
        <?= $usuario->nombreCompleto() ?>
        <small class="roboto-condensed-light"><?= $usuario->rol ?></small>
      </figcaption>
    </figure>
    <div class="full-box nav-lateral-bar"></div>
    <nav style="background: url(recursos/imagenes/nav-font.jpg) cover" class="full-box nav-lateral-menu">
      <ul>
        <?php foreach ($enlaces as $enlace) : ?>
          <?php if (!key_exists('subenlaces', $enlace)) : ?>
            <li>
              <a href="<?= $enlace['href'] ?? '#' ?>">
                <?= $enlace['icono'] ?>
                <?= $enlace['titulo'] ?>
              </a>
            </li>
          <?php else : ?>
            <li>
              <a href="#" class="nav-btn-submenu">
                <?= $enlace['icono'] ?>
                <?= $enlace['titulo'] ?>
                <i class="fas fa-chevron-down"></i>
              </a>
              <ul>
                <?php foreach ($enlace['subenlaces'] as $subenlace) : ?>
                  <li>
                    <a href="<?= $subenlace['href'] ?>">
                      <?= $subenlace['icono'] ?>
                      <?= $subenlace['titulo'] ?>
                    </a>
                  </li>
                <?php endforeach ?>
              </ul>
            </li>
          <?php endif ?>
        <?php endforeach ?>
      </ul>
    </nav>
  </div>
</aside>