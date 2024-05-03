<?php

use flight\template\View;
use SARCO\Modelos\Periodo;

assert($vistas instanceof View);
assert($ultimoPeriodo instanceof Periodo || $ultimoPeriodo === null);
scripts('./recursos/js/validarFormulario.js');

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
    'value' => $ultimoPeriodo?->siguientePeriodo() ?? date('Y')
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar'
  ]);

  ?>
</form>
