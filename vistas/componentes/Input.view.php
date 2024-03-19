<?php

/**
 * @var string $textoDeValidacion
 * @var string $name
 * @var string $placeholder
 * @var string $tipo
 * @var ?string $min
 * @var ?string $max
 * @var ?string $minlength
 * @var ?string $maxlength
 */

?>

<label class="input-group input-group--with-validation" data-validate="<?= $textoDeValidacion ?>">
  <input
    type="<?= $tipo ?? 'text' ?>"
    class="input-group__input"
    name="<?= $name ?>"
    placeholder="<?= $placeholder ?>"
    <?= isset($min) ? "min='$min'" : '' ?>
    <?= isset($max) ? "max='$max'" : '' ?>
    <?= isset($minlength) ? "minlength='$minlength'" : '' ?>
    <?= isset($maxlength) ? "maxlength='$maxlength'" : '' ?>
  />
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
