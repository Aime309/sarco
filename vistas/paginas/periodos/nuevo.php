<?php

use flight\template\View;
use SARCO\Modelos\Periodo;

assert($vistas instanceof View);
assert($ultimoPeriodo instanceof Periodo || $ultimoPeriodo === null);
scripts('./recursos/js/validarFormulario.js');
$ultimoAño = $ultimoPeriodo?->siguientePeriodo() ?? date('Y');
$siguienteAño = $ultimoAño + 1;

?>

<header class="full-box page-header">
  <h3 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Aperturar período
  </h3>
  <p class="text-justify"></p>
</header>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a class="active" href="./periodos/nuevo">
        <i class="fas fa-plus fa-fw"></i>
        &nbsp; Aperturar período
      </a>
    </li>
    <li>
      <a href="./periodos/">
        <i class="fas fa-clipboard-list fa-fw"></i>
        &nbsp; Lista de períodos
      </a>
    </li>
  </ul>
</div>

<form method="post" action="./periodos" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'El año de inicio es requerido',
    'name' => 'anio_inicio',
    'placeholder' => 'Año de inicio',
    'type' => 'number',
    'min' => 2006,
    'max' => date('Y') + 1,
    'value' => $_SESSION['datos']['anio_inicio'] ?? $ultimoAño,
    'onchange' => 'actualizarMomentos(this)'
  ]);

  echo <<<html
  <hr />
  <h5>Primer momento</h5>
  <div class="row">
    {$vistas->fetch('componentes/Input', [
      'class' => 'col mr-2',
      'validacion' => 'La fecha de inicio es requerida',
      'name' => 'momentos[1][inicio]',
      'placeholder' => 'Inicio',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][1]['inicio'] ?? "$ultimoAño-09-15"
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'momentos[1][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][1]['fin'] ?? "$ultimoAño-12-12"
    ])}
  </div>
  <hr />
  <h5>Segundo momento</h5>
  <div class="row">
    {$vistas->fetch('componentes/Input', [
      'class' => 'col mr-2',
      'validacion' => 'La fecha de inicio es requerida',
      'name' => 'momentos[2][inicio]',
      'placeholder' => 'Inicio',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][2]['inicio'] ?? "$siguienteAño-01-17"
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'momentos[2][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][2]['fin'] ?? "$siguienteAño-04-01"
    ])}
  </div>
  <hr />
  <h5>Tercer momento</h5>
  <div class="row">
    {$vistas->fetch('componentes/Input', [
      'class' => 'col mr-2',
      'validacion' => 'La fecha de inicio es requerida',
      'name' => 'momentos[3][inicio]',
      'placeholder' => 'Inicio',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][3]['inicio'] ?? "$siguienteAño-05-10"
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'momentos[3][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][3]['fin'] ?? "$siguienteAño-06-30"
    ])}
  </div>
  html;

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar'
  ]);

  ?>
</form>

<script>
  function actualizarMomentos($añoInicio) {
    $momentos = $añoInicio.form.querySelectorAll('[name^="momentos"]')

    $momentos.forEach(($momento, index) => {
      [, mes, dia] = $momento.value.split('-')

      if (index <= 1) {
        $momento.value = `${$añoInicio.value}-${mes}-${dia}`

        return
      }

      $momento.value = `${parseInt($añoInicio.value) + 1}-${mes}-${dia}`
    })
  }
</script>
