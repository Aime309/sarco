<?php

use flight\template\View;

assert($vistas instanceof View);

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Aperturar aula
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

<form method="post" action="./aulas" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'El código debe mínimo 3 letras, números y guiones',
    'name' => 'codigo',
    'placeholder' => 'Código',
    'minlength' => 3,
    'pattern' => '(?=.*[0-9])(?=.*[A-ZÑa-zñ])(?=.*-).{3,}',
    'value' => $_SESSION['datos']['codigo'] ?? ''
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
        'selected' => @$_SESSION['datos']['tipo'] === 'Pequeña'
      ],
      [
        'children' => 'Grande',
        'selected' => @$_SESSION['datos']['tipo'] === 'Grande'
      ],
    ]
  ])}
  html;

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Aperturar'
  ]);

  ?>
</form>
