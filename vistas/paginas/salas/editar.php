<?php

use flight\template\View;
use SARCO\Modelos\Sala;

assert($vistas instanceof View);
assert($sala instanceof Sala);
scripts('./recursos/js/validarFormulario.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Editar sala
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'El nombre sólo pueden contener letras',
    'name' => 'nombre',
    'placeholder' => 'Nombre',
    'minlength' => 3,
    'maxlength' => 40,
    'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
    'value' => $sala->nombre
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
    'max' => 10,
    'value' => $sala->edadMinima
  ])}

  {$vistas->fetch('componentes/Input', [
    'class' => 'col ml-2',
    'type' => 'number',
    'validacion' => 'La edad máxima es requerida',
    'name' => 'edad_maxima',
    'placeholder' => 'Edad máxima',
    'min' => 1,
    'max' => 10,
    'value' => $sala->edadMaxima
  ])}
  </div>
  html;

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Actualizar'
  ]);

  ?>
</form>
