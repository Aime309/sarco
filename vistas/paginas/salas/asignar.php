<?php

use flight\template\View;
use SARCO\Modelos\Aula;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Usuario;

assert($vistas instanceof View);

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Asignar maestros a sala
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" action="./salas/asignar" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?php

  $vistas->render('componentes/Select', [
    'validacion' => 'El período es requerido',
    'name' => 'id_periodo',
    'placeholder' => 'Período',
    'opciones' => array_map(static fn (Periodo $periodo): array => [
      'value' => $periodo->id,
      'children' => $periodo->inicio,
      'selected' => $periodo == $periodoActual
    ], $periodos)
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El maestro es requerido',
    'name' => 'id_maestro[1]',
    'placeholder' => 'Maestro 1',
    'onchange' => 'desactivarMaestros(this)',
    'opciones' => array_map(static fn (Usuario $maestro): array => [
      'value' => $maestro->id,
      'children' => $maestro->nombreCompleto()
    ], $maestros)
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El maestro es requerido',
    'name' => 'id_maestro[2]',
    'placeholder' => 'Maestro 2',
    'onchange' => 'desactivarMaestros(this)',
    'opciones' => array_map(static fn (Usuario $maestro): array => [
      'value' => $maestro->id,
      'children' => $maestro->nombreCompleto(),
    ], $maestros)
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El maestro es requerido',
    'name' => 'id_maestro[3]',
    'placeholder' => 'Maestro 3 (opcional)',
    'onchange' => 'desactivarMaestros(this)',
    'required' => false,
    'opciones' => array_map(static fn (Usuario $maestro): array => [
      'value' => $maestro->id,
      'children' => $maestro->nombreCompleto()
    ], $maestros)
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'La sala es requerida',
    'name' => 'id_sala',
    'placeholder' => 'Sala',
    'opciones' => array_map(static fn (Sala $sala): array => [
      'value' => $sala->id,
      'children' => "Sala $sala"
    ], $salas)
  ]);

  $vistas->render('componentes/Select', [
    'validacion' => 'El aula es requerida',
    'name' => 'id_aula',
    'placeholder' => 'Aula',
    'opciones' => array_map(static fn (Aula $aula): array => [
      'value' => $aula->id,
      'children' => "$aula->codigo ~ $aula->tipo ({$aula->capacidad()})"
    ], $aulas)
  ]);

  echo '<div class="row">';

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Asignar',
    'class' => 'col-md-7 mt-3 mr-md-2'
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'reset',
    'contenido' => 'Empezar de nuevo',
    'class' => 'col-md bg-secondary mt-3 ml-md-2'
  ]);

  echo '</div>';

  ?>
</form>

<script>
  document.querySelector('[type="reset"]').addEventListener('click', event => {
    document.querySelectorAll('option').forEach($option => {
      if ($option.getAttribute('data-type') !== 'placeholder') {
        $option.disabled = false
      }
    })
  })

  function desactivarMaestros($idMaestro) {
    const selectName = $idMaestro.name

    $idMaestro.form
      .querySelectorAll(`[value="${$idMaestro.value}"]`)
      .forEach($option => {
        if ($option.parentElement.name === selectName) {
          return
        }

        $option.disabled = true
      })
  }
</script>
