<?php

use Leaf\BareUI;

/**
 * @var BareUI $template
 */

?>

<form action="./asignar" method="post" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'El nombre sólo puede contener letras con la inicial en mayúscula',
      'name' => 'nombre',
      'placeholder' => 'Nombre del niño'
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => '',
      'name' => 'edad',
      'placeholder' => 'Edad',
      'tipo' => 'number',
      'min' => 1,
      'max' => 18
    ]
  ) ?>
  <?= $template::render(
    'componentes/Select',
    [
      'textoDeValidacion' => '',
      'name' => 'sala',
      'placeholder' => 'Sala',
      'opciones' => [
        ['valor' => 1, 'texto' => 'Sala 1'],
        ['valor' => 2, 'texto' => 'Sala 2'],
        ['valor' => 3, 'texto' => 'Sala 3']
      ]
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => "El período debe tener el formato 'AAAA-AAAA', ejemplo: 2023-2024",
      'name' => 'periodo',
      'placeholder' => 'Período',
      'minlength' => 9,
      'maxlength' => 9
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'Ejemplo: Momento 1, 2 o 3',
      'name' => 'momento',
      'placeholder' => 'Momento',
      'minlength' => 9,
      'maxlength' => 9
    ]
  ) ?>
  <?= $template::render('componentes/Boton', ['tipo' => 'submit', 'contenido' => 'Registrar']) ?>
</form>
