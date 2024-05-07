<?php

use flight\template\View;
use SARCO\Enumeraciones\EstadoCivil;
use SARCO\Enumeraciones\Genero;
use SARCO\Enumeraciones\GrupoSanguineo;
use SARCO\Enumeraciones\Nacionalidad;
use SARCO\Modelos\Estudiante;
use SARCO\Modelos\Momento;

assert($vistas instanceof View);
assert($ultimoMomento instanceof Momento);
$estudiantes = (static fn (Estudiante ...$estudiantes) => $estudiantes)(...$estudiantes);
$momentosParaIterar = [];

foreach ($momentos as $momento) {
  assert($momento instanceof Momento);

  $momentosParaIterar[$momento->periodo][] = $momento;
}

$momentosOpciones = '';
$asignaciones = [];

foreach ($momentosParaIterar as $periodo => $momentos) {
  $momentos = join('', array_map(
    static function (Momento $momento) use ($ultimoMomento, &$asignaciones): string {
      $selected = $ultimoMomento->id === $momento->id ? 'selected' : null;

      if ($selected) {
        $url = str_replace('/index.php', '', substr($_SERVER['SCRIPT_NAME'], 1));
        $host = $_SERVER['SERVER_NAME'];
        $puerto = $_SERVER['SERVER_PORT'];
        $protocolo = $_SERVER['REQUEST_SCHEME'];

        $url = "$protocolo://$host:$puerto/$url/api/salas/asignaciones/$momento->id";
        $respuesta = file_get_contents($url);
        $asignaciones = json_decode($respuesta, true);
      }

      $actual = $selected ? '~ actual' : null;

      return <<<html
      <option value="$momento->id" $selected>$momento $actual</option>
      html;
    },
    $momentos
  ));

  $momentosOpciones .= <<<html
  <optgroup label="$periodo">$momentos</optgroup>
  html;
}

$asignaciones = join('', array_map(static fn (array $asignacion): string => <<<html
<option value="{$asignacion['docente']['id']}">
  {$asignacion['docente']['nombre']} ~ {$asignacion['sala']}
</option>
html, $asignaciones));

scripts('./recursos/js/validarFormulario.js');

?>

<header class="full-box page-header">
  <h1 class="text-left">
    <i class="fab fa-dashcube fa-fw"></i>
    Inscribir estudiante
  </h1>
  <p class="text-justify"></p>
</header>

<?php if (!key_exists('cedula', $_GET)) : ?>
  <form class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
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
  </form>

  <details class="my-5">
    <summary class="h2 pl-5 mb-4">Inscribir por primera vez</summary>
    <form method="post" class="form form--bordered form--with-validation form--with-padding form--threequarter form--centered">
      <?php

      $vistas->render('componentes/Select', [
        'validacion' => 'El momento es requerido',
        'name' => 'id_momento',
        'placeholder' => 'Momento',
        'children' => $momentosOpciones,
        'onchange' => <<<'javascript'
        fetch(`api/salas/asignaciones/${this.value}`)
          .then(response => response.json())
          .then(asignaciones => {
            asignaciones = asignaciones.map(asignacion => `
              <option value='${asignacion.docente.id}'>
                ${asignacion.docente.nombre} ~ ${asignacion.sala}
              </option>
            `).join('')

            document.getElementById('docentes').innerHTML = asignaciones
            document.getElementById('asistentes').innerHTML = asignaciones
          })
        javascript
      ]);

      $vistas->render('componentes/Select', [
        'class' => 'col mr-2',
        'validacion' => 'La asignación de maestro es requerida',
        'name' => 'id_asignacion_docente',
        'placeholder' => 'Maestro',
        'children' => <<<html
        <optgroup label='Maestros' id='docentes'>{$asignaciones}</optgroup>
        html
      ]);

      $vistas->render('componentes/Select', [
        'class' => 'col ml-2',
        'validacion' => 'La asignación de asistente es requerida',
        'name' => 'id_asignacion_asistente',
        'placeholder' => 'Asistente',
        'children' => <<<html
        <optgroup label='Asistentes' id='asistentes'>{$asignaciones}</optgroup>
        html
      ]);

      echo '<fieldset class="mt-5"><legend>Datos del estudiante</legend>';

      $vistas->render('componentes/Input', [
        'validacion' => 'Los nombres sólo pueden contener letras',
        'name' => 'estudiante[nombres]',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Los apellidos sólo pueden contener letras',
        'name' => 'estudiante[apellidos]',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'estudiante[fecha_nacimiento]',
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date'
      ]);

      $vistas->render('componentes/Textarea', [
        'validacion' => 'El lugar de nacimiento es requerido',
        'name' => 'estudiante[lugar_nacimiento]',
        'placeholder' => 'Lugar de nacimiento',
        'minlength' => 3
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El género es requerido',
        'name' => 'estudiante[genero]',
        'placeholder' => 'Género',
        'opciones' => array_map(static fn (Genero $genero): array => [
          'value' => $genero->name,
          'children' => $genero->name
        ], Genero::cases())
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El tipo de sangre es requerido',
        'name' => 'estudiante[grupo_sanguineo]',
        'placeholder' => 'Tipo de sangre',
        'opciones' => array_map(static fn (GrupoSanguineo $grupo): array => [
          'value' => $grupo->value,
          'children' => $grupo->value
        ], GrupoSanguineo::cases())
      ]);

      echo '</fieldset><fieldset class="mt-5"><legend>Datos de la madre</legend>';

      $vistas->render('componentes/Input', [
        'validacion' => 'Los nombres sólo pueden contener letras',
        'name' => 'madre[nombres]',
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Los apellidos sólo pueden contener letras',
        'name' => 'madre[apellidos]',
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La cédula es requerida',
        'name' => 'madre[cedula]',
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'madre[fecha_nacimiento]',
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El estado civil es requerido',
        'name' => 'madre[estado_civil]',
        'placeholder' => 'Estado civil',
        'opciones' => array_map(static fn (EstadoCivil $estado): array => [
          'value' => $estado->name,
          'children' => $estado->name
        ], EstadoCivil::cases())
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'La nacionalidad es requerido',
        'name' => 'madre[nacionalidad]',
        'placeholder' => 'Nacionalidad',
        'opciones' => array_map(static fn (Nacionalidad $nacionalidad): array => [
          'value' => $nacionalidad->name,
          'children' => $nacionalidad->name
        ], Nacionalidad::cases())
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
        'type' => 'tel',
        'name' => 'madre[telefono]',
        'placeholder' => 'Teléfono',
        'minlength' => 15,
        'maxlength' => 15,
        'pattern' => '\+\d{2} \d{3}-\d{7}'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El correo es requerido',
        'type' => 'email',
        'name' => 'madre[correo]',
        'placeholder' => 'Correo',
        'minlength' => 5
      ]);

      echo '</fieldset><details class="mt-5"><summary class="h4 mb-4">Datos del padre (opcional)</summary>';

      $vistas->render('componentes/Input', [
        'validacion' => 'Los nombres sólo pueden contener letras',
        'name' => 'padre[nombres]',
        'required' => false,
        'placeholder' => 'Nombres',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'Los apellidos sólo pueden contener letras',
        'name' => 'padre[apellidos]',
        'required' => false,
        'placeholder' => 'Apellidos',
        'minlength' => 3,
        'maxlength' => 40,
        'pattern' => '[A-ZÁÉÍÓÚ][a-záéíóú]{2,19}(\s?|\s?[A-ZÁÉÍÓÚ][a-záéíóú]{2,19})'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La cédula es requerida',
        'name' => 'padre[cedula]',
        'required' => false,
        'placeholder' => 'Cédula',
        'type' => 'number',
        'min' => 1000000,
        'max' => 99999999
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'La fecha de nacimiento es requerida',
        'name' => 'padre[fecha_nacimiento]',
        'required' => false,
        'placeholder' => 'Fecha de nacimiento',
        'type' => 'date'
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'El estado civil es requerido',
        'name' => 'padre[estado_civil]',
        'required' => false,
        'placeholder' => 'Estado civil',
        'opciones' => array_map(static fn (EstadoCivil $estado): array => [
          'value' => $estado->name,
          'children' => $estado->name
        ], EstadoCivil::cases())
      ]);

      $vistas->render('componentes/Select', [
        'validacion' => 'La nacionalidad es requerido',
        'name' => 'padre[nacionalidad]',
        'required' => false,
        'placeholder' => 'Nacionalidad',
        'opciones' => array_map(static fn (Nacionalidad $nacionalidad): array => [
          'value' => $nacionalidad->name,
          'children' => $nacionalidad->name
        ], Nacionalidad::cases())
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El teléfono no es válido: +XX XXX-XXXX',
        'type' => 'tel',
        'name' => 'padre[telefono]',
        'required' => false,
        'placeholder' => 'Teléfono',
        'minlength' => 15,
        'maxlength' => 15,
        'pattern' => '\+\d{2} \d{3}-\d{7}'
      ]);

      $vistas->render('componentes/Input', [
        'validacion' => 'El correo es requerido',
        'type' => 'email',
        'name' => 'padre[correo]',
        'required' => false,
        'placeholder' => 'Correo',
        'minlength' => 5
      ]);

      echo '</details>';

      $vistas->render('componentes/Boton', [
        'tipo' => 'submit',
        'contenido' => 'Inscribir'
      ]);

      ?>
    </form>
  </details>

  <datalist id="estudiantes">
    <?php foreach ($estudiantes as $estudiante) : ?>
      <option value="<?= $estudiante->cedula ?>"></option>
    <?php endforeach ?>
  </datalist>
<?php endif ?>
