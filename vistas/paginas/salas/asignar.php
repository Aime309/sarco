<?php

use flight\template\View;
use SARCO\Modelos\Periodo;
use SARCO\Modelos\Sala;
use SARCO\Modelos\Usuario;

assert($vistas instanceof View);
scripts('./recursos/js/validarFormulario.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Asignar maestro a sala
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
    'name' => 'id_maestro',
    'placeholder' => 'Maestro',
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
      'children' => $sala
    ], $salas)
  ]);

  $vistas->render('componentes/Boton', [
    'tipo' => 'submit',
    'contenido' => 'Registrar'
  ]);

  ?>
</form>
