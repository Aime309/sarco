<?php

use flight\template\View;
use SARCO\Modelos\Periodo;

assert($vistas instanceof View);
assert($periodo instanceof Periodo);
scripts('./recursos/js/validarFormulario.js');
$ultimoAño = $periodo->inicio;

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Aperturar período
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" action="./periodos" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'El año de inicio es requerido',
    'name' => 'anio_inicio',
    'placeholder' => 'Año de inicio',
    'type' => 'number',
    'min' => 2006,
    'max' => date('Y') + 1,
    'value' => $ultimoAño
  ]);

  echo <<<html
  <hr />
  <h5>Primer momento</h5>
  <div class="row">
    {$vistas->fetch('componentes/Input', [
      'class' => 'col mr-2',
      'validacion' => 'La fecha de inicio es requerida',
      'name' => 'periodos[1][inicio]',
      'placeholder' => 'Inicio',
      'type' => 'date',
      'value' => "$ultimoAño-01-01"
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'periodos[1][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => "$ultimoAño-04-30"
    ])}
  </div>
  <hr />
  <h5>Segundo momento</h5>
  <div class="row">
    {$vistas->fetch('componentes/Input', [
      'class' => 'col mr-2',
      'validacion' => 'La fecha de inicio es requerida',
      'name' => 'periodos[2][inicio]',
      'placeholder' => 'Inicio',
      'type' => 'date',
      'value' => "$ultimoAño-05-01"
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'periodos[2][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => "$ultimoAño-07-31"
    ])}
  </div>
  <hr />
  <h5>Tercer momento</h5>
  <div class="row">
    {$vistas->fetch('componentes/Input', [
      'class' => 'col mr-2',
      'validacion' => 'La fecha de inicio es requerida',
      'name' => 'periodos[3][inicio]',
      'placeholder' => 'Inicio',
      'type' => 'date',
      'value' => "$ultimoAño-08-01"
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'periodos[3][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => "$ultimoAño-12-31"
    ])}
  </div>
  html;

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar'
  ]);

  ?>
</form>
