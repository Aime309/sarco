<?php

use flight\template\View;

scripts('recursos/js/ingreso.js');
assert($vistas instanceof View);

?>

<form method="post" action="ingresar" class="form form--full form--with-validation">
  <div class="form__background" style="background-image: url(recursos/imagenes/bg-01.jpg)"></div>
  <div class="form__body">
    <h1 class="form__title">Inicia sesión para continuar</h1>
    <?php

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'La cédula es requerida',
        'name' => 'cedula',
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999
      ]
    );

    echo $vistas->fetch(
      'componentes/Input',
      [
        'validacion' => 'La contraseña es requerida',
        'name' => 'clave',
        'placeholder' => 'Contraseña',
        'type' => 'password',
        'pattern' => '(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,}',
        'minlength' => 8
      ]
    );

    ?>
    <div class="form__remember">
      <label class="checkbox" style="visibility: hidden">
        <input class="checkbox__input" name="recordar" type="checkbox" />
        <span class="checkbox__label">Recuérdame</span>
      </label>
      <a href="registrate">¿No tienes cuenta? Regístrate</a>
    </div>
    <button class="button">Ingresar</button>
  </div>
</form>
