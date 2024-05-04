<?php

use flight\template\View;

assert($vistas instanceof View);
scripts('./recursos/js/validarFormulario.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Aperturar sala
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" action="./salas" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'El nombre sólo pueden contener letras y números',
    'name' => 'nombre',
    'placeholder' => 'Nombre',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[\wáéíóúñÁÉÍÓÚÑ\s]{3,20}'
  ]);

  echo <<<html
  <div class="row">
  {$vistas->fetch('componentes/Input', [
    'class' => 'col mr-2',
    'type' => 'number',
    'validacion' => 'La edad mínima es requerida',
    'name' => 'edad_minima',
    'placeholder' => 'Edad mínima',
    'min' => 0,
    'max' => 10
  ])}

  {$vistas->fetch('componentes/Input', [
    'class' => 'col ml-2',
    'type' => 'number',
    'validacion' => 'La edad máxima es requerida',
    'name' => 'edad_maxima',
    'placeholder' => 'Edad máxima',
    'min' => 1,
    'max' => 10
  ])}
  </div>
  html;

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar'
  ]);

  ?>
</form>
