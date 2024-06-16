<?php

use flight\template\View;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Usuario;

assert($usuario instanceof Usuario);
assert($vistas instanceof View);

/**
 * @var array{
 *   href: string,
 *   icono: string,
 *   titulo: string,
 *   subenlaces?: array{href: string, icono: string, titulo: string}[]
 * }[]
 */
$enlaces = [
  [
    'href' => './',
    'icono' => '<i class="fab fa-dashcube fa-fw"></i>',
    'titulo' => 'Inicio'
  ]
];

if (!$usuario->esDocente()) {
  $enlaces[] = [
    'icono' => '<i class="fas fa-users fa-fw"></i>',
    'titulo' => 'Usuarios',
    'subenlaces' => [
      [
        'href' => 'usuarios/nuevo',
        'icono' => '<i class="fas fa-plus fa-fw"></i>',
        'titulo' => 'Nuevo usuario'
      ],
      [
        'href' => 'usuarios',
        'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>',
        'titulo' => 'Lista de usuarios'
      ],
    ]
  ];
}

$enlaces[] = [
  'icono' => '<i class="fas fa-graduation-cap fa-fw"></i>',
  'titulo' => 'Estudiantes',
  'subenlaces' => [

    $usuario->esDocente() || $usuario->esDirector() ?: [
      'href' => 'estudiantes/inscribir',
      'icono' => '<i class="fas fa-plus fa-fw"></i>',
      'titulo' => 'Inscribir estudiante'
    ] + [
      'href' => 'inscripciones',
      'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>',
      'titulo' => 'Lista de inscripciones'
    ],
    [
      'href' => 'estudiantes',
      'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>',
      'titulo' => 'Lista de estudiantes'
    ],
    [
      'href' => '#buscar-estudiante',
      'icono' => '<i class="fas fa-search fa-fw"></i>',
      'titulo' => 'Buscar estudiante',
      'data-toggle' => 'modal'
    ],
    [
      'href' => 'estudiantes/boletines',
      'icono' => '<i class="fas fa-search fa-fw"></i>',
      'titulo' => 'Lista de boletines'
    ],
  ]
];

if (!$usuario->esDocente()) {
  $enlaces[] = [
    'icono' => '<i class="fas fa-swatchbook fa-fw"></i>',
    'titulo' => 'Salas',
    'subenlaces' => [
      [
        'href' => 'salas/nueva',
        'icono' => '<i class="fas fa-plus fa-fw"></i>',
        'titulo' => 'Registrar Sala'
      ],
      [
        'href' => 'salas',
        'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>',
        'titulo' => 'Lista de Salas'
      ],
      [
        'href' => 'salas/asignar',
        'icono' => '<i class="fas fa-pen-to-square fa-fw"></i>',
        'titulo' => 'Asignar sala'
      ],
    ]
  ];
}

$enlaces[] = [
  'icono' => '<i class="fas fa-person-chalkboard fa-fw"></i>',
  'titulo' => 'Maestros',
  'subenlaces' => [
    [
      'href' => 'usuarios/nuevo?rol=maestro',
      'icono' => '<i class="fas fa-plus fa-fw"></i>',
      'titulo' => 'Registrar Maestro'
    ],
    [
      'href' => 'maestros',
      'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>',
      'titulo' => 'Lista de Maestros'
    ],
  ]
];

$enlaces[] = [
  'icono' => '<i class="fas fa-people-roof fa-fw"></i>',
  'titulo' => 'Representantes',
  'href' => 'representantes'
];

if ($usuario->esDirector()) {
  $enlaces[] = [
    'icono' => '<i class="fas fa-calendar fa-fw"></i>',
    'titulo' => 'Periodos',
    'subenlaces' => [
      [
        'href' => 'periodos/nuevo',
        'icono' => '<i class="fas fa-plus fa-fw"></i>',
        'titulo' => 'Aperturar Período'
      ],
      [
        'href' => 'periodos',
        'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>',
        'titulo' => 'Lista de Períodos'
      ],
    ]
  ];

  $enlaces[] = [
    'icono' => '<i class="fas fa-school fa-fw"></i>',
    'titulo' => 'Aulas',
    'subenlaces' => [
      [
        'href' => 'aulas/nueva',
        'icono' => '<i class="fas fa-plus fa-fw"></i>',
        'titulo' => 'Aperturar Aula'
      ],
      [
        'href' => 'aulas',
        'icono' => '<i class="fas fa-clipboard-list fa-fw"></i>',
        'titulo' => 'Lista de Aulas'
      ],
    ]
  ];

  $enlaces[] = [
    'icono' => '<i class="fas fa-gears fa-fw"></i>',
    'titulo' => 'Configuraciones',
    'subenlaces' => [
      [
        'href' => 'respaldar',
        'icono' => '<i class="fas fa-floppy-disk fa-fw"></i>',
        'titulo' => 'Respaldar sistema'
      ],
      !$puedeRestaurar ?: [
        'href' => 'restaurar',
        'icono' => '<i class="fas fa-rotate-left fa-fw"></i>',
        'titulo' => 'Restaurar sistema'
      ],
    ]
  ];
}

?>

<aside class="full-box nav-lateral" style="background-image: url(recursos/imagenes/nav-font.jpg)">
  <div class="full-box nav-lateral-bg show-nav-lateral"></div>
  <div class="full-box nav-lateral-content">
    <figure class="full-box nav-lateral-avatar">
      <i class="far fa-times-circle show-nav-lateral"></i>
      <img src="recursos/imagenes/logo.jpg" class="img-fluid rounded-circle" />
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
                <?php foreach ($enlace['subenlaces'] as $subenlace) :
                  if (is_bool($subenlace)) continue ?>
                  <li>
                    <a data-toggle="<?= $subenlace['data-toggle'] ?? '' ?>" href="<?= $subenlace['href'] ?>">
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

<div class="modal fade" id="buscar-estudiante">
  <div class="modal-dialog">
    <form action="./estudiantes" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buscar estudiante</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php

        $vistas->render('componentes/Select', [
          'validacion' => 'La cédula escolar es requerida',
          'name' => 'cedula',
          'placeholder' => 'Cédula escolar',
          'opciones' => array_map(fn (Estudiante $estudiante) => [
            'value' => $estudiante->cedula,
            'children' => "$estudiante->cedula ~ {$estudiante->nombreCompleto()}"
          ], $estudiantes)
        ]);

        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Cancelar
        </button>
        <button class="btn btn-primary">Buscar</button>
      </div>
    </form>
  </div>
</div>
