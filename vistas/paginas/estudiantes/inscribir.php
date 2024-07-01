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
        'opciones' => array_map(static fn(Periodo $periodo): array => [
          'value' => $periodo->id,
          'children' => $periodo,
          'selected' => (
            @$_SESSION['datos']['id_periodo'] === $periodo->id
            || $periodo == $periodoActual
          )
        ], $periodos)
      ]);

      echo '<fieldset class="mt-5 row justify-content-center"><legend>Datos del estudiante</legend>';

      $vistas->render('componentes/Input', [
        'validacion' => 'Debe tener mínimo 1 nombre',
        'pattern' => '(\s?[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}){1,5}',
        'name' => 'estudiante[nombres]',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['estudiante']['nombres']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Debe tener mínimo 2 apellidos',
        'pattern' => '[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s{1}([a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s?){1,3}',
        'name' => 'estudiante[apellidos]',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'class' => 'col-md-5 ml-md-2',
        'value' => @$_SESSION['datos']['estudiante']['apellidos']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'estudiante[fecha_nacimiento]',
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date',
        'class' => 'col-md-5 mr-md-2',
        'onblur' => 'obtenerSalas(this)',
        'onchange' => 'calcularEdad(this)',
        'value' => @$_SESSION['datos']['estudiante']['fecha_nacimiento']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Edad',
        'readonly' => true,
        'disabled' => true,
        'name' => 'estudiante[edad]',
        'placeholder' => 'Edad',
        'class' => 'col-md-5 ml-md-2',
      ]);

      $vistas->render('componentes/Textarea', [
        'validacion' => 'El lugar de nacimiento es requerido',
        'name' => 'estudiante[lugar_nacimiento]',
        'placeholder' => 'Lugar de nacimiento',
        'minlength' => 3,
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['estudiante']['lugar_nacimiento']
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El género es requerido',
        'name' => 'estudiante[genero]',
        'placeholder' => 'Género',
        'opciones' => array_map(static fn(Genero $genero): array => [
          'value' => $genero->name,
          'children' => $genero->name,
          'selected' => @$_SESSION['datos']['estudiante']['genero'] === $genero->name
        ], Genero::cases()),
        'class' => 'col-md-5 ml-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El tipo de sangre es requerido',
        'name' => 'estudiante[grupo_sanguineo]',
        'placeholder' => 'Tipo de sangre',
        'opciones' => array_map(static fn(GrupoSanguineo $grupo): array => [
          'value' => $grupo->value,
          'children' => $grupo->value,
          'selected' => @$_SESSION['datos']['estudiante']['grupo_sanguineo'] === $grupo->value
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
        'class' => 'col-md m-2',
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

      $vistas->render('componentes/Input', [
        'validacion' => '',
        'readonly' => true,
        'name' => 'id_asignacion_sala',
        'placeholder' => '',
        'class' => 'd-none',
      ]);

      echo '</div></fieldset><fieldset class="mt-5 row justify-content-center"><legend>Datos de la madre</legend>';

      $vistas->render('componentes/Input', [
        'validacion' => 'Debe tener mínimo 1 nombre',
        'pattern' => '(\s?[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}){1,5}',
        'name' => 'madre[nombres]',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['madre']['nombres']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Debe tener mínimo 2 apellidos',
        'pattern' => '[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s{1}([a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s?){1,3}',
        'name' => 'madre[apellidos]',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'class' => 'col-md-5 ml-md-2',
        'value' => @$_SESSION['datos']['madre']['apellidos']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La cédula es requerida',
        'name' => 'madre[cedula]',
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999,
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['madre']['cedula']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'madre[fecha_nacimiento]',
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date',
        'class' => 'col-md-5 ml-md-2',
        'value' => @$_SESSION['datos']['madre']['fecha_nacimiento']
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El estado civil es requerido',
        'name' => 'madre[estado_civil]',
        'placeholder' => 'Estado civil',
        'opciones' => array_map(static fn(EstadoCivil $estado): array => [
          'value' => $estado->name,
          'children' => $estado->name,
          'selected' => @$_SESSION['datos']['madre']['estado_civil'] === $estado->name
        ], EstadoCivil::cases()),
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'La nacionalidad es requerido',
        'name' => 'madre[nacionalidad]',
        'placeholder' => 'Nacionalidad',
        'opciones' => array_map(static fn(Nacionalidad $nacionalidad): array => [
          'value' => $nacionalidad->name,
          'children' => $nacionalidad->name,
          'selected' => @$_SESSION['datos']['madre']['nacionalidad'] === $nacionalidad->name
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
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['madre']['telefono']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El correo es requerido',
        'type' => 'email',
        'name' => 'madre[correo]',
        'placeholder' => 'Correo',
        'minlength' => 5,
        'class' => 'col-md-5 ml-md-2',
        'value' => @$_SESSION['datos']['madre']['correo']
      ]);

      echo '</fieldset><details class="mt-5"><summary class="h4 mb-4">Datos del padre (opcional)</summary>';
      echo '<div class="row justify-content-center">';

      $vistas->render('componentes/Input', [
        'validacion' => 'Debe tener mínimo 1 nombre',
        'pattern' => '(\s?[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}){1,5}',
        'name' => 'padre[nombres]',
        'required' => false,
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['padre']['nombres']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Debe tener mínimo 2 apellidos',
        'pattern' => '[a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s{1}([a-záéíóúñA-ZÁÉÍÓÚÑ]{1,20}\s?){1,3}',
        'name' => 'padre[apellidos]',
        'required' => false,
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'class' => 'col-md-5 ml-md-2',
        'value' => @$_SESSION['datos']['padre']['apellidos']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La cédula es requerida',
        'name' => 'padre[cedula]',
        'required' => false,
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999,
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['padre']['cedula']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'padre[fecha_nacimiento]',
        'required' => false,
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date',
        'class' => 'col-md-5 ml-md-2',
        'value' => @$_SESSION['datos']['padre']['fecha_nacimiento']
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El estado civil es requerido',
        'name' => 'padre[estado_civil]',
        'required' => false,
        'placeholder' => 'Estado civil',
        'opciones' => array_map(static fn(EstadoCivil $estado): array => [
          'value' => $estado->name,
          'children' => $estado->name,
          'selected' => @$_SESSION['datos']['padre']['estado_civil'] === $estado->name
        ], EstadoCivil::cases()),
        'class' => 'col-md-5 mr-md-2'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'La nacionalidad es requerido',
        'name' => 'padre[nacionalidad]',
        'required' => false,
        'placeholder' => 'Nacionalidad',
        'opciones' => array_map(static fn(Nacionalidad $nacionalidad): array => [
          'value' => $nacionalidad->name,
          'children' => $nacionalidad->name,
          'selected' => @$_SESSION['datos']['padre']['nacionalidad'] === $nacionalidad->name
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
        'class' => 'col-md-5 mr-md-2',
        'value' => @$_SESSION['datos']['padre']['telefono']
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El correo es requerido',
        'type' => 'email',
        'name' => 'padre[correo]',
        'required' => false,
        'placeholder' => 'Correo',
        'minlength' => 5,
        'class' => 'col-md-5 ml-md-2',
        'value' => @$_SESSION['datos']['padre']['correo']
      ]);

      echo '</div></details>';
      echo '<div class="row">';

      $vistas->render('componentes/Boton', [
        'tipo' => 'submit',
        'contenido' => 'Inscribir',
        'onclick' => 'this.form.checkValidity() && this.form.submit()',
        'class' => 'col-md-7 mt-3 mr-md-2'
      ]);

      $vistas->render('componentes/Boton', [
        'tipo' => 'reset',
        'contenido' => 'Empezar de nuevo',
        'class' => 'col-md bg-secondary mt-3 ml-md-2',
        'onclick' => "resetInputs(this.closest('form'))"
      ]);

      echo '</div>';

      ?>
    </form>
  </details>
<?php endif ?>

<script>
  function resetInputs($form) {
    fetch('./sesion/limpiar')

    $form.querySelectorAll('input').forEach($input => $input.setAttribute('value', ''))
    $form.querySelectorAll('textarea').forEach($textarea => {
      $textarea.innerText = ''
    })
  }

  const $idPeriodo = document.querySelector('[name="id_periodo"]')
  const $idSala = $idPeriodo.form.querySelector('[name="id_sala"]')
  const $idAula = $idPeriodo.form.querySelector('[name="id_aula"]')
  const $idDocente1 = $idPeriodo.form.querySelector('[name="id_maestro[1]"]')
  const $idDocente2 = $idPeriodo.form.querySelector('[name="id_maestro[2]"]')
  const $idDocente3 = $idPeriodo.form.querySelector('[name="id_maestro[3]"]')
  const $idAsignacion = $idPeriodo.form.querySelector('[name="id_asignacion_sala"]')
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
      $idSala.innerHTML = $idSala.firstElementChild.outerHTML
    } else {
      reiniciarSelectoresDeSala()
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

    if (!body.docentes.length || !body.aula || !body.idAsignacion) {
      return
    }

    const capacidad = body.aula.tipo === 'Pequeña' ? '28-29' : '31-32'
    $idAula.value = `${body.aula.codigo} ~ ${body.aula.tipo} (${capacidad})`

    $idDocente1.value = `${body.docentes[0].nombres} ${body.docentes[0].apellidos}`
    $idDocente2.value = `${body.docentes[1].nombres} ${body.docentes[1].apellidos}`

    if (body.docentes[2]) {
      $idDocente3.value = `${body.docentes[2].nombres} ${body.docentes[2].apellidos}`
    } else {
      $idDocente3.value = ''
    }

    $idAsignacion.value = body.idAsignacion

    if (body.inscripcionesExcedidas) {
      new Noty({
        text: `<span style="margin-right: 1em">❌</span> Inscripciones excedidas`,
        type: 'error',
        theme,
        timeout: 3000
      }).show()
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

    if (isNaN(edad)) {
      return
    }

    $edad.value = edad
  }

  function reiniciarSelectoresDeSala() {
    $idSala.innerHTML = $idSala.firstElementChild.outerHTML
    $idAula.value = ''
    $idDocente1.value = ''
    $idDocente2.value = ''
    $idDocente3.value = ''
  }
</script>
