<?php

use Leaf\BareUI;

/** @var BareUI $template */

$scripts('assets/js/ingreso.js');

?>

<form method="post" action="ingresar" class="form form--full form--with-validation">
  <div class="form__background" style="background-image: url(assets/images/bg-01.jpg)"></div>
  <div class="form__body">
    <h1 class="form__title">Inicia sesión para continuar</h1>
    <?php

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'La cédula es requerida',
        'name' => 'cedula',
        'placeholder' => 'Cédula',
        'tipo' => 'number',
        'min' => 1000000,
        'max' => 99999999
      ]
    );

    echo $template::render(
      'componentes/Input',
      [
        'textoDeValidacion' => 'La contraseña es requerida',
        'name' => 'clave',
        'placeholder' => 'Contraseña',
        'tipo' => 'password'
      ]
    );

    ?>
    <div class="form__remember">
      <label class="checkbox">
        <input class="checkbox__input" type="checkbox" />
        <span class="checkbox__label">Recuérdame</span>
      </label>
      <a href="./registrate">¿No tienes cuenta? Regístrate</a>
    </div>
    <button class="button">Ingresar</button>
  </div>
</form>
