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
    Editar período
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" action="./periodos/<?= $periodo->inicio ?>" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <input type="hidden" name="id_periodo" value="<?= $periodo->id ?>" />
  <?php

  $vistas->render('componentes/Input', [
    'validacion' => 'El año de inicio es requerido',
    'name' => 'anio_inicio',
    'placeholder' => 'Año de inicio',
    'type' => 'number',
    'min' => 2006,
    'max' => date('Y') + 1,
    'value' => $_SESSION['datos']['anio_inicio'] ?? $ultimoAño,
    'onkeyup' => 'actualizarMomentos()'
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
      'value' => $_SESSION['datos']['momentos'][1]['inicio']
        ?? $periodo->momento(1)->inicio('Y-m-d')
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'momentos[1][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][1]['fin']
        ?? $periodo->momento(1)->cierre('Y-m-d')
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
      'value' => $_SESSION['datos']['momentos'][2]['inicio']
        ?? $periodo->momento(2)->inicio('Y-m-d')
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'momentos[2][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][2]['fin']
        ?? $periodo->momento(2)->cierre('Y-m-d')
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
      'value' => $_SESSION['datos']['momentos'][3]['inicio']
        ?? $periodo->momento(3)->inicio('Y-m-d')
    ])}
    {$vistas->fetch('componentes/Input', [
      'class' => 'col ml-2',
      'validacion' => 'La fecha de fin es requerida',
      'name' => 'momentos[3][fin]',
      'placeholder' => 'Fin',
      'type' => 'date',
      'value' => $_SESSION['datos']['momentos'][3]['fin']
        ?? $periodo->momento(3)->cierre('Y-m-d')
    ])}
  </div>
  html;

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Actualizar'
  ]);

  ?>
</form>

<script>
  $añoInicio = document.querySelector('[name="anio_inicio"]')
  $momentos = $añoInicio.form.querySelectorAll('[name^="momentos"]')

  function actualizarMomentos() {
    if ($añoInicio.value.length !== 4) {
      return
    }

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
