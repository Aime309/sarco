<?php

use flight\template\View;
use SARCO\Enumeraciones\EstadoCivil;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\GrupoSanguineo;
use SARCO\Enumeraciones\Nacionalidad;
use SARCO\Modelos\Periodo;

assert($vistas instanceof View);
assert($periodoActual instanceof Periodo);

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Inscribir estudiante
  </h1>
  <p class="text-justify"></p>
</header>

<?php if (!key_exists('cedula', $_GET)) : ?>
  <!-- <form class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
    <?php

    $vistas->render('componentes/Input', [
      'validacion' => 'La cédula escolar debe seguir el formato v-1xxXXXXXXXX',
      'name' => 'cedula',
      'placeholder' => 'Buscar cédula escolar...',
      'pattern' => 'v-1[0-9]{2}[0-9]{7,8}',
      'list' => 'estudiantes'
    ]);

    $vistas->render('componentes/Boton', [
      'tipo' => 'submit',
      'contenido' => 'Buscar'
    ]);

    ?>
  </form> -->

  <details class="my-5" open>
    <summary class="h2 pl-5 mb-4">Inscribir por primera vez</summary>
    <form method="post" class="form form--bordered form--with-validation form--with-padding mx-5 form--centered">
      <?php

      $vistas->render('componentes/Select', [
        'validacion' => 'El período es requerido',
        'name' => 'id_periodo',
        'placeholder' => 'Período',
        'opciones' => array_map(static fn (Periodo $periodo): array => [
          'value' => $periodo->id,
          'children' => $periodo,
          'selected' => $periodo == $periodoActual
        ], $periodos)
      ]);

      echo '<fieldset class="mt-5 row justify-content-center"><legend>Datos del estudiante</legend>';

      $vistas->render('componentes/Input', [
        'validacion' => 'Los nombres sólo pueden contener letras',
        'name' => 'estudiante[nombres]',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Los apellidos sólo pueden contener letras',
        'name' => 'estudiante[apellidos]',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'estudiante[fecha_nacimiento]',
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date',
        'class' => 'col-md-5 mr-md-2',
        'onblur' => 'obtenerSalas(this)',
        'onchange' => 'calcularEdad(this)'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Edad',
        'readonly' => true,
        'disabled' => true,
        'name' => 'estudiante[edad]',
        'placeholder' => 'Edad',
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Textarea', [
        'validacion' => 'El lugar de nacimiento es requerido',
        'name' => 'estudiante[lugar_nacimiento]',
        'placeholder' => 'Lugar de nacimiento',
        'minlength' => 3,
        'class' => 'col-md-5 mr-md-2',
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El género es requerido',
        'name' => 'estudiante[genero]',
        'placeholder' => 'Género',
        'opciones' => array_map(static fn (Genero $genero): array => [
          'value' => $genero->name,
          'children' => $genero->name
        ], Genero::cases()),
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El tipo de sangre es requerido',
        'name' => 'estudiante[grupo_sanguineo]',
        'placeholder' => 'Tipo de sangre',
        'opciones' => array_map(static fn (GrupoSanguineo $grupo): array => [
          'value' => $grupo->value,
          'children' => $grupo->value
        ], GrupoSanguineo::cases()),
        'class' => 'col-md-5'
      ]);

      echo '</fieldset><fieldset class="mt-5"><legend>Sala asignada</legend>';

      echo '<div class="row">';

      $vistas->render('componentes/Select', [
        'validacion' => 'La sala es requerida',
        'name' => 'id_sala',
        'placeholder' => 'Salas disponibles',
        'class' => 'col-md m-2',
        'onchange' => 'actualizarAulaDocentes(this)'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Aula asignada',
        'readonly' => true,
        'disabled' => true,
        'name' => 'id_aula',
        'placeholder' => 'Aula asignada',
        'class' => 'col-md m-2'
      ]);

      echo '</div><div class="row">';

      $vistas->render('componentes/Input', [
        'validacion' => 'Maestro asignado',
        'readonly' => true,
        'disabled' => true,
        'name' => 'id_maestro[1]',
        'placeholder' => 'Maestro asignado',
        'class' => 'col-md m-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Maestro asignado',
        'readonly' => true,
        'disabled' => true,
        'name' => 'id_maestro[2]',
        'placeholder' => 'Maestro asignado',
        'class' => 'col-md m-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Maestro asignado',
        'readonly' => true,
        'disabled' => true,
        'name' => 'id_maestro[3]',
        'placeholder' => 'Maestro asignado',
        'class' => 'col-md m-2'
      ]);

      echo '</div></fieldset><fieldset class="mt-5 row justify-content-center"><legend>Datos de la madre</legend>';

      $vistas->render('componentes/Input', [
        'validacion' => 'Los nombres sólo pueden contener letras',
        'name' => 'madre[nombres]',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Los apellidos sólo pueden contener letras',
        'name' => 'madre[apellidos]',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La cédula es requerida',
        'name' => 'madre[cedula]',
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999,
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'madre[fecha_nacimiento]',
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date',
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El estado civil es requerido',
        'name' => 'madre[estado_civil]',
        'placeholder' => 'Estado civil',
        'opciones' => array_map(static fn (EstadoCivil $estado): array => [
          'value' => $estado->name,
          'children' => $estado->name
        ], EstadoCivil::cases()),
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'La nacionalidad es requerido',
        'name' => 'madre[nacionalidad]',
        'placeholder' => 'Nacionalidad',
        'opciones' => array_map(static fn (Nacionalidad $nacionalidad): array => [
          'value' => $nacionalidad->name,
          'children' => $nacionalidad->name
        ], Nacionalidad::cases()),
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
        'type' => 'tel',
        'name' => 'madre[telefono]',
        'placeholder' => 'Teléfono',
        'minlength' => 15,
        'maxlength' => 15,
        'pattern' => '\+\d{2} \d{3}-\d{7}',
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El correo es requerido',
        'type' => 'email',
        'name' => 'madre[correo]',
        'placeholder' => 'Correo',
        'minlength' => 5,
        'class' => 'col-md-5 ml-md-2'
      ]);

      echo '</fieldset><details class="mt-5"><summary class="h4 mb-4">Datos del padre (opcional)</summary>';
      echo '<div class="row justify-content-center">';

      $vistas->render('componentes/Input', [
        'validacion' => 'Los nombres sólo pueden contener letras',
        'name' => 'padre[nombres]',
        'required' => false,
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Los apellidos sólo pueden contener letras',
        'name' => 'padre[apellidos]',
        'required' => false,
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})',
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La cédula es requerida',
        'name' => 'padre[cedula]',
        'required' => false,
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999,
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'padre[fecha_nacimiento]',
        'required' => false,
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date',
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El estado civil es requerido',
        'name' => 'padre[estado_civil]',
        'required' => false,
        'placeholder' => 'Estado civil',
        'opciones' => array_map(static fn (EstadoCivil $estado): array => [
          'value' => $estado->name,
          'children' => $estado->name
        ], EstadoCivil::cases()),
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'La nacionalidad es requerido',
        'name' => 'padre[nacionalidad]',
        'required' => false,
        'placeholder' => 'Nacionalidad',
        'opciones' => array_map(static fn (Nacionalidad $nacionalidad): array => [
          'value' => $nacionalidad->name,
          'children' => $nacionalidad->name
        ], Nacionalidad::cases()),
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
        'type' => 'tel',
        'name' => 'padre[telefono]',
        'required' => false,
        'placeholder' => 'Teléfono',
        'minlength' => 15,
        'maxlength' => 15,
        'pattern' => '\+\d{2} \d{3}-\d{7}',
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El correo es requerido',
        'type' => 'email',
        'name' => 'padre[correo]',
        'required' => false,
        'placeholder' => 'Correo',
        'minlength' => 5,
        'class' => 'col-md-5 ml-md-2'
      ]);

      echo '</div></details>';

      $vistas->render('componentes/Boton', [
        'tipo' => 'submit',
        'contenido' => 'Inscribir'
      ]);

      ?>
    </form>
  </details>
<?php endif ?>

<script>
  const $idPeriodo = document.querySelector('[name="id_periodo"]')
  const $idSala = $idPeriodo.form.querySelector('[name="id_sala"]')
  const $idAula = $idPeriodo.form.querySelector('[name="id_aula"]')
  const $idDocente1 = $idPeriodo.form.querySelector('[name="id_maestro[1]"]')
  const $idDocente2 = $idPeriodo.form.querySelector('[name="id_maestro[2]"]')
  const $idDocente3 = $idPeriodo.form.querySelector('[name="id_maestro[3]"]')
  const $edad = $idPeriodo.form.querySelector('[name="estudiante[edad]"]')

  $idPeriodo.onchange = () => {
    obtenerSalas(document.querySelector('[name="estudiante[fecha_nacimiento]"]'))
  }

  async function obtenerSalas($fechaNacimiento) {
    if (!$fechaNacimiento.value) {
      return
    }

    const url = `./api/asignaciones/${$idPeriodo.value}/${$fechaNacimiento.value}`
    const respuesta = await fetch(url)
    const asignaciones = await respuesta.json()

    if (asignaciones.length) {
      $idSala.innerHTML = ''
    }

    Object.entries(asignaciones).forEach(([, asignacion]) => {
      $idSala.innerHTML += `
        <option value="${asignacion.idSala}">
          Sala ${asignacion.nombreSala}
        </option>
      `

      actualizarAulaDocentes($idSala)
    })
  }

  async function actualizarAulaDocentes($idSala) {
    const url = `./api/asignaciones/${$idPeriodo.value}/${$idSala.value}`
    const response = await fetch(url)
    const body = await response.json()

    const capacidad = body.aula.tipo === 'Pequeña' ? '28-29' : '31-32'
    $idAula.value = `${body.aula.codigo} ~ ${body.aula.tipo} (${capacidad})`

    if (!body.docentes.length) {
      return
    }

    $idDocente1.value = `${body.docentes[0].nombres} ${body.docentes[0].apellidos}`
    $idDocente2.value = `${body.docentes[1].nombres} ${body.docentes[1].apellidos}`

    if (body.docentes[2]) {
      $idDocente3.value = `${body.docentes[2].nombres} ${body.docentes[2].apellidos}`
    } else {
      $idDocente3.value = ''
    }
  }

  function calcularEdad($fechaNacimiento) {
    if (!$fechaNacimiento.value) {
      return
    }

    const fechaActual = new Date()
    const fechaNacimiento = new Date($fechaNacimiento.value)
    let diferencia = fechaActual.getTime() - fechaNacimiento.getTime()
    let edad = new Date(diferencia).getFullYear() - 1970

    $edad.value = edad
  }
</script>
