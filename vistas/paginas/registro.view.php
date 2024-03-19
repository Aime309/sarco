<?php

use Leaf\BareUI;
use SARCO\Modelos\Rol;

/**
 * @var BareUI $template
 */

$scripts('assets/js/registro.js')

?>

<form action="registrate" method="post" class="form form--full form--with-validation">
  <div class="form__body">
    <h1 class="form__title">Cree su cuenta de director/a</h1>
    <?= $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'El nombre es requerido',
        'name' => 'nombre',
        'placeholder' => 'Nombre'
      ]
    ) ?>
    <?= $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'El apellido es requerido',
        'name' => 'apellido',
        'placeholder' => 'Apellido'
      ]
    ) ?>
    <?= $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'La cédula es requerido',
        'name' => 'cedula',
        'placeholder' => 'Cédula',
        'tipo' => 'number',
        'min' => 1
      ]
    ) ?>
    <?= $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'La contraseña es requerida',
        'name' => 'clave',
        'placeholder' => 'Contraseña',
        'tipo' => 'password'
      ]
    ) ?>
    <input hidden name="id_rol" value="<?= Rol::Director->value ?>" />
    <button class="button">Registrarse</button>
  </div>
  <div class="form__background" style="background-image: url(assets/images/OIG1.jpeg)"></div>
</form>
