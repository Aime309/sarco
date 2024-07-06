<?php

use flight\template\View;
use SARCO\Modelos\Boletin;

assert($boletin instanceof Boletin);
assert($vistas instanceof View);

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Editar boletín
  </h1>
  <p class="text-justify"></p>
</header>

<form action="./estudiantes/boletines/<?= $boletin->id ?>" method="post" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'Estudiante',
    'name' => 'estudiante',
    'placeholder' => 'Estudiante',
    'value' => "$boletin->cedulaEstudiante ~ $boletin->nombresEstudiante $boletin->apellidosEstudiante",
    'disabled' => true,
    'readonly' => true
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'Momento',
    'name' => 'momento',
    'placeholder' => 'Momento',
    'value' => $boletin->momento(),
    'disabled' => true,
    'readonly' => true
  ]);

  $vistas->render('componentes/Input', [
    'validacion' => 'El número de inasistencias es requerido',
    'name' => 'inasistencias',
    'placeholder' => 'N° de inasistencias',
    'type' => 'number',
    'min' => 0,
    'max' => 366,
    'value' => $boletin->inasistencias
  ]);

  $vistas->render('componentes/Textarea', [
    'validacion' => 'El nombre del proyecto es requerido',
    'name' => 'proyecto',
    'placeholder' => 'Nombre del proyecto',
    'minlength' => 3,
    'maxlength' => 40,
    'value' => $boletin->proyecto !== 'No establecido' ? $boletin->proyecto : ''
  ]);

  $vistas->render('componentes/Textarea', [
    'validacion' => 'La descripción de formación es requerida',
    'name' => 'formacion',
    'placeholder' => 'Descripción de formación',
    'minlength' => 3,
    'maxlength' => 200,
    'value' => $boletin->descripcionFormacion !== 'No establecida'
      ? $boletin->descripcionFormacion
      : ''
  ]);

  $vistas->render('componentes/Textarea', [
    'validacion' => 'La descripción de ambiente es requerida',
    'name' => 'ambiente',
    'placeholder' => 'Descripción de ambiente',
    'minlength' => 3,
    'maxlength' => 200,
    'value' => $boletin->descripcionAmbiente !== 'No establecida'
      ? $boletin->descripcionAmbiente
      : ''
  ]);

  $vistas->render('componentes/Textarea', [
    'validacion' => 'Las recomendaciones son requeridas',
    'name' => 'recomendaciones',
    'placeholder' => 'Recomendaciones',
    'minlength' => 3,
    'maxlength' => 150,
    'value' => $boletin->recomendaciones !== 'No establecidas'
      ? $boletin->recomendaciones
      : ''
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Actualizar'
  ]);

  // dd($boletin);

  ?>
</form>
