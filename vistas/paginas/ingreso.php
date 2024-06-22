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

    $vistas->render('componentes/Input', [
      'validacion' => 'La cédula es requerida',
      'name' => 'cedula',
      'placeholder' => 'Cédula',
      'type' => 'number',
      'min' => 1000000,
      'max' => 99999999
    ]);

    $vistas->render('componentes/Input', [
      'validacion' => 'La contraseña es requerida',
      'name' => 'clave',
      'placeholder' => 'Contraseña',
      'type' => 'password',
      'pattern' => '(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,}',
      'minlength' => 8
    ]);

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

<script>
  document.querySelector('[href="registrate"]').addEventListener('click', evento => {
    evento.preventDefault()
    $loader.show()

    fetch(evento.target.href)
      .then(respuesta => {
        if (respuesta.redirected) {
          new Noty({
            text: '<span style="margin-right: 1em">❌</span> Ya existe un director activo',
            type: 'error',
            theme: 'semanticui',
            timeout: 10000
          }).show()

          $loader.hide()
        } else {
          location.href = evento.target.href
        }
      })
  })

  document.querySelector('form').addEventListener('submit', evento => {
    evento.preventDefault()
    $loader.show()

    fetch('./api/ingresar', {
      method: evento.target.method,
      body: new FormData(evento.target)
    }).then(async respuesta => {
      if (respuesta.redirected) {
        location.reload()
      } else {
        new Noty({
          text: `<span style="margin-right: 1em">❌</span> ${await respuesta.text()}`,
          type: 'error',
          theme: 'semanticui',
          timeout: 10000
        }).show()

        $loader.hide()
      }
    })
  })
</script>
