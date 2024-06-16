<?php

use flight\template\View;
use SARCO\Modelos\Aula;

assert($vistas instanceof View);
assert($aula instanceof Aula);

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Editar aula
  </h1>
  <p class="text-justify"></p>
</header>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a class="active" href="./aulas/nueva">
        <i class="fas fa-plus fa-fw"></i>
        &nbsp; Aperturar aula
      </a>
    </li>
    <li>
      <a href="./aulas/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de aulas
      </a>
    </li>
  </ul>
</div>

<form method="post" action="./aulas/<?= $aula->id ?>" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'El código debe mínimo 3 letras, números y guiones',
    'name' => 'codigo',
    'placeholder' => 'Código',
    'minlength' => 3,
    'pattern' => '(?=.*[0-9])(?=.*[A-ZÑa-zñ])(?=.*-).{3,}',
    'value' => $aula->codigo
  ]);

  echo <<<html
  {$vistas->fetch('componentes/Select', [
    'validacion' => 'El tipo es requerido',
    'name' => 'tipo',
    'placeholder' => 'Tipo de aula',
    'required' => true,
    'opciones' => [
      [
        'children' => 'Pequeña',
        'selected' => $aula->esPequeña()
      ],
      [
        'children' => 'Grande',
        'selected' => !$aula->esPequeña()
      ],
    ]
  ])}
  html;

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Actualizar'
  ]);

  ?>
</form>
