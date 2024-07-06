<?php

use SARCO\Modelos\Boletin;
use SARCO\Modelos\Usuario;

/**
 * @var Boletin $boletin
 * @var string $root
 */

$docentes = $boletin->docentes();
$docentes = array_map(fn (Usuario $usuario) => $usuario->nombreCompleto(), $docentes);

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width">
  <base href="<?= $root ?>" />
  <title>SARCO | <?= $titulo ?></title>
  <link rel="icon" href="recursos/imagenes/favicon.png" />
  <link rel="stylesheet" href="recursos/1719944965_portada/styles.css" />
  <link rel="stylesheet" href="recursos/css/fuentes.css" />
  <style>
    .dancing-script {
      font-family: 'Dancing Script';
      font-size: 1.25em;
    }
  </style>
</head>

<body>
  <div class="wcdiv wcpage" style="width:612pt; height:792pt;">
    <div class="wcdiv" style="left:85.05pt; top:70.85pt;">
      <div class="wcdiv" style="top:44.98pt;">
        <a name="_GoBack" style="left:0pt; top:0pt;">
        </a>
      </div>
    </div>
    <div class="wcdiv" style="left:66.75pt; top:20.85pt;">
      <div class="wcdiv" style="left:-3pt; top:-3pt; width:646px; height:1008px;">
        <object type="image/svg+xml" data="recursos/1719944965_portada/1719944965_portada-1.svg"></object>
      </div>
    </div>
    <div class="wcdiv" style="left:144pt; top:46.5pt;">
      <div class="wcdiv" style="clip:rect(3.85pt,323.75pt,86.4pt,0.25pt);">
        <div class="wcdiv" style="left:7.45pt; top:3.85pt;">
          <span class="wcspan wctext001" style="left:28.21pt; top:0.39pt; line-height:13.41pt;">REPUBLICA</span>
          <span class="wcspan wctext001" style="left:98.21pt; top:0.39pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:101.55pt; top:0.39pt; line-height:13.41pt;">BOLIVARIANA</span>
          <span class="wcspan wctext001" style="left:184.88pt; top:0.39pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:188.21pt; top:0.39pt; line-height:13.41pt;">DE</span>
          <span class="wcspan wctext001" style="left:204.88pt; top:0.39pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:208.22pt; top:0.39pt; line-height:13.41pt;">VENEZUELA</span>
          <span class="wcspan wctext001" style="left:71.88pt; top:14.19pt; line-height:13.41pt;">M.P.P</span>
          <span class="wcspan wctext001" style="left:104.55pt; top:14.19pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:107.89pt; top:14.19pt; line-height:13.41pt;">PARA</span>
          <span class="wcspan wctext001" style="left:141.89pt; top:14.19pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:145.22pt; top:14.19pt; line-height:13.41pt;">LA</span>
          <span class="wcspan wctext001" style="left:161.22pt; top:14.19pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:164.55pt; top:14.19pt; line-height:13.41pt;">EDUCACION</span>
          <span class="wcspan wctext001" style="left:52.88pt; top:27.99pt; line-height:13.41pt;">C.E.I.N</span>
          <span class="wcspan wctext001" style="left:91.55pt; top:27.99pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:94.88pt; top:27.99pt; line-height:13.41pt;">“TEOTISTE</span>
          <span class="wcspan wctext001" style="left:159.55pt; top:27.99pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:162.89pt; top:27.99pt; line-height:13.41pt;">DE</span>
          <span class="wcspan wctext001" style="left:179.56pt; top:27.99pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:182.89pt; top:27.99pt; line-height:13.41pt;">GALLEGOS”</span>
          <span class="wcspan wctext001" style="left:75.88pt; top:41.79pt; line-height:13.41pt;">CAJA</span>
          <span class="wcspan wctext001" style="left:108.55pt; top:41.79pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:111.89pt; top:41.79pt; line-height:13.41pt;">SECA</span>
          <span class="wcspan wctext001" style="left:145.23pt; top:41.79pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:148.56pt; top:41.79pt; line-height:13.41pt;">SUCRE-</span>
          <span class="wcspan wctext001" style="left:194.56pt; top:41.79pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:197.9pt; top:41.79pt; line-height:13.41pt;">ZULIA</span>
          <span class="wcspan wctext001" style="left:76.52pt; top:55.59pt; line-height:13.41pt;">CODIGO</span>
          <span class="wcspan wctext001" style="left:125.19pt; top:55.59pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:128.52pt; top:55.59pt; line-height:13.41pt;">DEA:</span>
          <span class="wcspan wctext001" style="left:157.85pt; top:55.59pt; line-height:13.41pt;"> </span>
          <span class="wcspan wctext001" style="left:161.19pt; top:55.59pt; line-height:13.41pt;">OD06382825</span>
        </div>
      </div>
    </div>
    <div class="wcdiv">
      <img class="wcimg" style="left:421.7pt; top:39.01pt; width:116.91pt; height:110.5pt;" src="recursos/1719944965_portada/1719944965_portada-2.png" />
    </div>
    <div class="wcdiv">
      <img class="wcimg" style="left:99pt; top:51.1pt; width:63.65pt; height:62.9pt;" src="recursos/1719944965_portada/1719944965_portada-3.png" />
    </div>
    <div class="wcdiv">
      <img class="wcimg" style="left:102.6pt; top:118.15pt; width:92.47pt; height:49.75pt;" src="recursos/1719944965_portada/1719944965_portada-4.png" />
    </div>
    <div class="wcdiv" style="left:119.25pt; top:193.48pt;">
      <div class="wcdiv" style="left:-1.13pt; top:-1.13pt; width:195px; height:246px;">
        <object type="image/svg+xml" data="recursos/1719944965_portada/1719944965_portada-5.svg"></object>
      </div>
    </div>
    <div class="wcdiv" style="left:175.88pt; top:407.98pt;">
      <div class="wcdiv" style="clip:rect(3.85pt,260pt,55.65pt,0.25pt);">
        <div class="wcdiv" style="left:7.45pt; top:3.85pt;">
          <span class="wcspan wctext001" style="font-size:20pt; left:6.02pt; top:0.65pt; line-height:22.34pt;">BOLETIN</span>
          <span class="wcspan wctext001" style="font-size:20pt; left:93.79pt; top:0.65pt; line-height:22.34pt;"> </span>
          <span class="wcspan wctext001" style="font-size:20pt; left:99.35pt; top:0.65pt; line-height:22.34pt;">INFORMATIVO</span>
        </div>
      </div>
    </div>
    <div class="wcdiv" style="left:82.5pt; top:466.48pt;">
      <div class="wcdiv" style="left:-0.25pt; top:-0.25pt; width:594px; height:373px;">
        <object type="image/svg+xml" data="recursos/1719944965_portada/1719944965_portada-6.svg"></object>
      </div>
      <div class="wcdiv" style="clip:rect(3.85pt,444.5pt,276.15pt,0.25pt);">
        <div class="wcdiv" style="left:7.45pt; top:3.85pt;">
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:0.52pt; line-height:17.88pt;">Nombres</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:69.35pt; top:0.52pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:73.8pt; top:0.52pt; line-height:17.88pt;">y</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:82.7pt; top:0.52pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:87.14pt; top:0.52pt; line-height:17.88pt;">Apellidos:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:164.48pt; top:0.52pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:168.92pt; top:0.52pt; line-height:17.88pt;"><u class="dancing-script"><?= $boletin->estudiante() ?></u></span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:28.38pt; line-height:17.88pt;">Lugar</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:44.45pt; top:28.38pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:48.89pt; top:28.38pt; line-height:17.88pt;">y</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:57.79pt; top:28.38pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:62.23pt; top:28.38pt; line-height:17.88pt;">Fecha</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:108.48pt; top:28.38pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:112.92pt; top:28.38pt; line-height:17.88pt;">de</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:131.59pt; top:28.38pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:136.04pt; top:28.38pt; line-height:17.88pt;">Nacimiento:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:227.61pt; top:28.38pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:232.05pt; top:28.38pt; line-height:17.88pt;">______________________</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:56.23pt; line-height:17.88pt;">_______________________________________________</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:84.08pt; line-height:17.88pt;">Edad:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:44.45pt; top:84.08pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:48.89pt; top:84.08pt; line-height:17.88pt;">___________</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:165.91pt; top:84.08pt; line-height:17.88pt;">Cedula</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:219.26pt; top:84.08pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:223.7pt; top:84.08pt; line-height:17.88pt;">Escolar:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:286.84pt; top:84.08pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:291.29pt; top:84.08pt; line-height:17.88pt;"><u class="dancing-script"><?= $boletin->cedulaEstudiante ?></u></span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:111.94pt; line-height:17.88pt;">Representante:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:116.48pt; top:111.94pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:120.92pt; top:111.94pt; line-height:17.88pt;">__________________________________</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:423.47pt; top:111.94pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:139.79pt; line-height:17.88pt;">C.I:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:25.77pt; top:139.79pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:30.22pt; top:139.79pt; line-height:17.88pt;">V-________________________</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:167.65pt; line-height:17.88pt;">Dirección:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:78.24pt; top:167.65pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:82.69pt; top:167.65pt; line-height:17.88pt;">______________________________________</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:195.5pt; line-height:17.88pt;">Docentes:</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:77.35pt; top:195.5pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:81.8pt; top:195.5pt; line-height:17.88pt;"><u class="dancing-script"><?= join(' y ', $docentes) ?></u></span>
          <span class="wcspan wctext001" style="font-size:16pt; left:0pt; top:223.35pt; line-height:17.88pt;">Director(a):</span>
          <span class="wcspan wctext001" style="font-size:16pt; left:86.23pt; top:223.35pt; line-height:17.88pt;"> </span>
          <span class="wcspan wctext001" style="font-size:16pt; left:90.68pt; top:223.35pt; line-height:17.88pt;">_____________________________________</span>
        </div>
      </div>
    </div>
    <div class="wcdiv">
      <img class="wcimg" style="left:274.25pt; top:179.15pt; width:215.5pt; height:225.45pt;" src="recursos/1719944965_portada/1719944965_portada-7.png" />
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // window.print()
    })
  </script>
</body>

</html>
