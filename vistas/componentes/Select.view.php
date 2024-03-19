<?php

/**
 * @var string $textoDeValidacion
 * @var string $name
 * @var string $placeholder
 * @var array<int, array{valor: string, texto: string}> $opciones
 */

?>

<label class="select-group input-group input-group--with-validation" data-validate="<?= $textoDeValidacion ?>">
  <select
    type="<?= $tipo ?? 'text' ?>"
    class="input-group__input"
    name="<?= $name ?>"
    placeholder="<?= $placeholder ?>"
    <?= isset($min) ? "min='$min'" : '' ?>
    <?= isset($max) ? "max='$max'" : '' ?>
    <?= isset($minlength) ? "minlength='$minlength'" : '' ?>
    <?= isset($maxlength) ? "maxlength='$maxlength'" : '' ?>
  >
    <option selected disabled>Selecciona una opción</option>
    <?php foreach ($opciones as $opcion): ?>
      <option value="<?= $opcion['valor'] ?>"><?= $opcion['texto'] ?></option>
    <?php endforeach ?>
  </select>
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
