<?php

use SARCO\Modelos\Boletin;
use SARCO\Modelos\Usuario;

/**
 * @var Boletin[] $boletines
 * @var Usuario $director
 */

$edadEstudiante = $boletines[0]->obtenerEstudiante()->edad();
$boletin = $boletines[0];

$lapsos = [
  1 => 'PRIMER LAPSO',
  2 => 'SEGUNDO LAPSO',
  3 => 'TERCER LAPSO'
];

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>SARCO | <?= $titulo ?></title>
    <base href="<?= $root ?>/" />
    <link rel="icon" href="recursos/imagenes/favicon.png" />
    <link rel="stylesheet" href="recursos/css/fuentes.css" />
    <link rel="stylesheet" href="recursos/css/boletin.css" />
  </head>
  <body>
    <div class="page">
      <header class="page__header-container">
        <div class="page__header-img">
          <img src="recursos/boletin/1719944965_portada-3.png" width="84" />
          <img src="recursos/boletin/1719944965_portada-4.png" />
        </div>
        <h2 class="page__header">
          REPÚBLICA BOLIVARIANA DE VENEZUELA <br />
          M.P.P PARA LA EDUCACIÓN <br />
          C.E.I.N “TEOTISTE DE GALLEGOS” <br />
          CAJA SECA SUCRE-ZULIA <br />
          CODIGO DEA: OD06382825
        </h2>
        <img class="page__header-img" src="recursos/boletin/1719944965_portada-2.png" />
      </header>
      <div class="page__photo-container">
        <picture class="page__photo-placeholder">
          <img src="recursos/imagenes/logo.jpg" width="100%" />
        </picture>
        <img src="recursos/boletin/1719944965_portada-7.png" height="300" />
      </div>
      <img class="page__title" src="recursos/boletin/titulo.png" />
      <article class="page__student-data">
        <div class="page__student-row">
          <span class="page__student-property">Nombres y apellidos:</span>
          <u class="page__student-value"><?= $boletin->obtenerEstudiante() ?></u>
        </div>
        <div class="page__student-row">
          <span class="page__student-property">Lugar y fecha de nacimiento:</span>
          <u class="page__student-value">
            <?= $boletin->obtenerEstudiante()->lugarNacimiento ?>
            /
            <?= $boletin->obtenerEstudiante()->fechaNacimiento() ?>
          </u>
        </div>
        <div class="page__student-row">
          <span class="page__student-property">Edad:</span>
          <u class="page__student-value">
            <?= $edadEstudiante === 1 ? '1 año' : "$edadEstudiante años" ?>
          </u>
          <span class="page__student-property">Cédula escolar:</span>
          <u class="page__student-value">
            <?= $boletin->obtenerEstudiante()->cedula ?>
          </u>
        </div>
        <div class="page__student-row">
          <span class="page__student-property">Representante:</span>
          <u class="page__student-value">
            <?= $boletin->obtenerEstudiante()->mama()->nombreCompleto() ?>
          </u>
        </div>
        <div class="page__student-row">
          <span class="page__student-property">C.I. V-</span>
          <u class="page__student-value"><?= $boletin->obtenerEstudiante()->mama()->cedula ?></u>
        </div>
        <div class="page__student-row">
          <!-- <span class="page__student-property">Dirección:</span> -->
          <!-- <u class="page__student-value">Dirección</u> -->
        </div>
        <div class="page__student-row">
          <span class="page__student-property">Docentes:</span>
          <u class="page__student-value"><?= join(', ', $boletin->docentes()) ?></u>
        </div>
        <div class="page__student-row">
          <span class="page__student-property">Director:</span>
          <u class="page__student-value"><?= $director ?></u>
        </div>
      </article>
    </div>
    <?php foreach ($boletines as $index => $boletin) : ?>
      <div class="page page--3-rows page--padding">
        <h2 class="page__title">MIS PROGRESOS DURANTE EL <?= $lapsos[$boletin->momento()->numero] ?></h2>
        <article class="page__descriptions">
          <div class="page__student-row">
            <span class="page__student-property">Estatura del niño(a):</span>
            <u class="page__student-value">__________</u>
            <span class="page__student-property">Peso:</span>
            <u class="page__student-value">_________</u>
          </div>
          <div class="page__student-row">
            <span class="page__student-property">N° de inasistencias del niño(a):</span>
            <u class="page__student-value">
              __<?= $boletin->inasistencias ?>____
            </u>
          </div>
          <?php if ($boletin->momento()->numero !== 1) : ?>
            <div class="page__student-row">
              <span class="page__student-property">Nombre del proyecto:</span>
              <u class="page__student-value">
                __<?= $boletin->proyecto ?>____
              </u>
            </div>
          <?php endif ?>
          <div class="page__student-row">
            <span class="page__student-property">
              Breve descripción: Formación personal, social y comunicación:
            </span>
            <u class="page__student-value">
              <?= $boletin->descripcionFormacion . str_repeat('_', 175 - mb_strlen($boletin->descripcionFormacion) ?: 0) ?>
            </u>
          </div>
          <br />
          <div class="page__student-row">
            <span class="page__student-property">
              Breve descripción: Relación entre los componentes del ambiente:
            </span>
            <u class="page__student-value">
              <?= $boletin->descripcionAmbiente . str_repeat('_', 170 - mb_strlen($boletin->descripcionAmbiente) ?: 0) ?>
            </u>
          </div>
          <br />
          <div class="page__student-row">
            <span class="page__student-property">
              Recomendaciones al representante:
            </span>
            <u class="page__student-value">
              <?= $boletin->recomendaciones . str_repeat('_', 115 - mb_strlen($boletin->recomendaciones) ?: 0) ?>
            </u>
          </div>
        </article>
        <footer class="page__signatures">
          <div class="page__signature">
            <hr />
            <span>Docente</span>
          </div>
          <div class="page__signature">
            <hr />
            <span>Director(a)</span>
          </div>
          <div class="page__signature">
            <hr />
            <span>Docente</span>
          </div>
          <div class="page__signature">
            <hr />
            <span>Representante</span>
          </div>
        </footer>
      </div>
    <?php endforeach ?>
    <div class="page page--padding">
      <h2 class="page__title">
        CONSTITUCIÓN DE LA REPÚBLICA BOLIVARIANA DE VENEZUELA
      </h2>
      <blockquote class="page__cite">
        “Toda persona tiene derecho a una educación integral de calidad,
        permanente, en igualdad de condiciones y oportunidades, sin más
        limitaciones que las derivadas de sus aptitudes, vocación y
        aspiraciones. La educación es obligatoria en todos sus niveles, desde
        el maternal hasta el nivel medio diversificado” (Artículo 103)
      </blockquote>
      <img class="page__lopna-img" src="recursos/boletin/lopna.png" />
      <h3 class="page__lopna-title">
        LEY ORGÁNICA PARA LA PROTECCIÓN DEL NIÑO, NIÑA Y DEL ADOLESCENTE
      </h3>
      <blockquote class="page__cite">
        “Todos los niños, niñas y adolescentes tienen el derecho a ser
        informados e informadas y a participar activamente en su proceso
        educativo” (Artículo 55)
      </blockquote>
      <h2 class="page__lopna-footer">
        ART. 54 LOPNA ASISTIR Y PARTICIPAR ACTIVAMENTE EN LA EDUCACION DE
        LOS NIÑOS
      </h2>
    </div>
    <div class="page page--padding">
      <h2 class="page__title">A MI REPRESENTANTE</h2>
      <article class="page__recomendations">
        Yo te ruego:
        <ul>
          <li>
            Colabora y Participa activamente con las actividades de mi escuela.
          </li>
          <li>
            Debes ser puntual a la hora de llegada y de salida de la institución.
          </li>
          <li>
            No discutas mis dificultades y equivocaciones cuando esté presente.
          </li>
          <li>
            Evita rayar y ensuciar el informe de mis logros.
          </li>
          <li>
            Asiste con vestimenta adecuada a la Institución.
          </li>
          <li>
            Contribuir con el autofinanciamiento de la Institución.
          </li>
          <li>
            Debes devolver este informe en un periodo de 5 días después de su
            entregado.
          </li>
        </ul>
        Gracias por siempre...
      </article>
      <img class="page__recomendations-img page__recomendations-img--right" src="recursos/boletin/school-bus.png" />
      <img class="page__recomendations-img page__recomendations-img--left" src="recursos/boletin/family.png" />
    </div>
  </body>
</html>
