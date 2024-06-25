<?php

use SARCO\Modelos\Representante;

assert($representante instanceof Representante);

?>

<div class="full-box page-header">
  <h1 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i>
    <?= $representante->nombreCompleto() ?>
  </h1>
  <div class="text-right">
    <button class="btn btn-danger" onclick="imprimir()">
      <i class="fas fa-print"></i>
      Imprimir
    </button>
  </div>
</div>

<div class="table-responsive px-3">
  <table class="table table-bordered table-hover">
    <tr>
      <th>Nombre completo:</th>
      <td><?= $representante->nombreCompleto() ?></td>
    </tr>
    <tr>
      <th>Cédula:</th>
      <td><?= $representante->cedula ?></td>
    </tr>
    <tr>
      <th>Edad</th>
      <th><?= $representante->edad() ?></th>
    </tr>
    <tr>
      <th>Correo</th>
      <th>
        <a href="mailto:<?= $representante->correo ?>">
          <?= $representante->correo ?>
        </a>
      </th>
    </tr>
    <tr>
      <th>Teléfono</th>
      <th>
        <a href="tel:<?= str_replace([' ', '-'], '', $representante->telefono) ?>">
          <?= $representante->telefono ?>
        </a>
      </th>
    </tr>
    <tr>
      <th>Estado civil</th>
      <th><?= $representante->estadoCivil ?></th>
    </tr>
  </table>
</div>

<section class="p-4">
  <h3>Mis estudiantes:</h3>
  <div class="row">
    <?php foreach ($representante->estudiantes() as $estudiante) : ?>
      <a href="./estudiantes/<?= $estudiante->cedula ?>" target="_blank" class="col-md-3">
        <article class="card pt-2">
          <img src="./node_modules/@fortawesome/fontawesome-free/svgs/solid/user.svg" class="card-img-top w-25 mx-auto" />
          <h4 class="card-header h6 text-center">
            <?= $estudiante->nombreCompleto() ?>
          </h4>
          <ul class="list-group">
            <li class="list-group-item"><?= $estudiante->cedula ?></li>
            <li class="list-group-item"><?= $estudiante->genero ?></li>
          </ul>
        </article>
      </a>
    <?php endforeach ?>
  </div>
</section>

<script>
  function imprimir() {
    if (!document.querySelector('.nav-lateral').classList.contains('active')) {
      document.querySelector('.show-nav-lateral').click()
    }

    print()
  }
</script>
