<?php

/**
 * @var string $textoDeValidacion
 * @var string $name
 * @var string $placeholder
 * @var ?string $minlength
 * @var ?string $maxlength
 * @var ?string $value
 * @var ?string $pattern
 */

?>

<label class="input-group input-group--with-validation" data-validate="<?= $textoDeValidacion ?>">
  <textarea
    class="input-group__input input-group__input--textarea"
    name="<?= $name ?>"
    placeholder="<?= $placeholder ?>"
    <?= isset($minlength) ? "minlength='$minlength'" : '' ?>
    <?= isset($maxlength) ? "maxlength='$maxlength'" : '' ?>
    <?= isset($pattern) ? "pattern='$pattern'" : '' ?>
    <?= isset($textoDeValidacion) ? "title='$textoDeValidacion'" : '' ?>
    rows="1"
  ><?= $value ?? '' ?></textarea>
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
