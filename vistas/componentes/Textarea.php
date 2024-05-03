<?php

assert(is_string($validacion));
assert(is_string($name));
assert(is_string($placeholder));

$minlength = isset($minlength) ? (int) $minlength : null;
$maxlength = isset($maxlength) ? (int) $maxlength : null;
$value = isset($value) ? (string) $value : null;
$pattern = isset($pattern) ? (string) $pattern : null;

?>

<label class="input-group input-group--with-validation" data-validate="<?= $validacion ?>">
  <textarea class="input-group__input input-group__input--textarea" name="<?= $name ?>" placeholder="<?= $placeholder ?>" <?= $minlength ? "minlength='$minlength'" : '' ?> <?= $maxlength ? "maxlength='$maxlength'" : '' ?> <?= $pattern ? "pattern='$pattern'" : '' ?> title="<?= $validacion ?>" rows="1"><?= $value ?></textarea>
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
