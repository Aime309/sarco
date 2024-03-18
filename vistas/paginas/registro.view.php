<?php

use SARCO\Modelos\Rol;

$scripts('assets/js/registro.js')

?>

<form action="registrate" method="post" class="form form--full form--with-validation">
  <div class="form__body">
    <h1 class="form__title">Cree su cuenta de director/a</h1>
    <label class="input-group input-group--with-validation" data-validate="El nombre es requerido">
      <input class="input-group__input" name="nombre" placeholder="Nombre" />
      <span class="input-group__focus"></span>
      <span class="input-group__label">Nombre</span>
    </label>
    <label class="input-group input-group--with-validation" data-validate="El apellido es requerido">
      <input class="input-group__input" name="apellido" placeholder="Apellido" />
      <span class="input-group__focus"></span>
      <span class="input-group__label">Apellido</span>
    </label>
    <label class="input-group input-group--with-validation" data-validate="La cédula es requerido">
      <input class="input-group__input" type="number" min="0" name="cedula" placeholder="Cédula" />
      <span class="input-group__focus"></span>
      <span class="input-group__label">Cédula</span>
    </label>
    <label class="input-group input-group--with-validation" data-validate="La contraseña es requerida">
      <input class="input-group__input" type="password" name="clave" placeholder="Contraseña" />
      <span class="input-group__focus"></span>
      <span class="input-group__label">Contraseña</span>
    </label>
    <input hidden name="id_rol" value="<?= Rol::Director->value ?>" />
    <button class="button">Registrarse</button>
  </div>
  <div class="form__background" style="background-image: url(assets/images/OIG1.jpeg)"></div>
</form>
