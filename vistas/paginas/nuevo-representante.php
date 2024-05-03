<?php

use Leaf\BareUI;

/**
 * @var BareUI $template
 */

$scripts('assets/js/registrar-representante.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Registro de representante
  </h1>
  <p class="text-justify"></p>
</header>

<form method="post" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'El nombre es requerido',
      'name' => 'fullname',
      'placeholder' => 'Nombres'
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'El apellido es requerido',
      'name' => 'lastname',
      'placeholder' => 'Apellidos'
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'La cédula es requerida',
      'name' => 'cedula',
      'placeholder' => 'Cédula',
      'tipo' => 'number',
      'min' => 1
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'La edad es requerida',
      'name' => 'age',
      'placeholder' => 'Edad',
      'tipo' => 'number',
      'min' => 18,
      'max' => 100
    ]
  ) ?>
  <?= $template::render(
    'componentes/Select',
    [
      'textoDeValidacion' => 'El sexo es requerido',
      'name' => 'sexo',
      'placeholder' => 'Sexo',
      'opciones' => [
        ['valor' => 'Masculino', 'texto' => 'Masculino'],
        ['valor' => 'Femenino', 'texto' => 'Femenino']
      ]
    ]
  ) ?>
  <?= $template::render(
    'componentes/Select',
    [
      'textoDeValidacion' => 'El estado civil es requerido',
      'name' => 'marital_status',
      'placeholder' => 'Estado civil',
      'opciones' => [
        ['valor' => 'S', 'texto' => 'Soltero(a)'],
        ['valor' => 'C', 'texto' => 'Casado(a)'],
        ['valor' => 'D', 'texto' => 'Divorciado(a)'],
        ['valor' => 'V', 'texto' => 'Viudo(a)'],
      ]
    ]
  ) ?>
  <?= $template::render(
    'componentes/Select',
    [
      'textoDeValidacion' => 'La nacionalidad es requerida',
      'name' => 'nationality',
      'placeholder' => 'Nacionalidad',
      'opciones' => [
        ['valor' => 'V', 'texto' => 'Venezolano(a)'],
        ['valor' => 'E', 'texto' => 'Extranjero(a)'],
      ]
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'La fecha de nacimiento es requerida',
      'name' => 'dob',
      'placeholder' => 'Fecha de nacimiento',
      'tipo' => 'date',
      'min' => '1970-01-01',
      'max' => (date('Y') - 18) . '-01-01'
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'El teléfono es requerido',
      'name' => 'phone',
      'placeholder' => 'Teléfono',
      'tipo' => 'tel',
      'minlength' => '11',
      'maxlength' => '16'
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'El correo electrónico es requerido',
      'name' => 'email',
      'placeholder' => 'Correo electrónico',
      'tipo' => 'email'
    ]
  ) ?>
  <?= $template::render(
    'componentes/Input',
    [
      'textoDeValidacion' => 'La dirección es requerida',
      'name' => 'address',
      'placeholder' => 'Dirección'
    ]
  ) ?>

  <?= $template::render('componentes/Boton', ['tipo' => 'submit', 'contenido' => 'Registrar']) ?>
</form>
