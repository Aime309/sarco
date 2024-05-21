<?php

assert(is_string($validacion));
assert(is_string($name));
assert(is_string($placeholder));

$minlength = isset($minlength) ? (int) $minlength : null;
$maxlength = isset($maxlength) ? (int) $maxlength : null;
$value = isset($value) ? (string) $value : null;
$required = isset($required) ? (bool) $required : true;
$pattern = isset($pattern) ? (string) $pattern : null;
$class = isset($class) ? (string) $class : null;

?>

<label
  class="input-group input-group--with-validationp <?= $required ? 'input-group--required' : 'input-group--optional' ?> <?= $class ?>"
  data-validate="<?= $validacion ?>">
  <textarea
    <?= $required ? 'required' : '' ?>
    class="input-group__input input-group__input--textarea"
    name="<?= $name ?>"
    placeholder="<?= $placeholder ?>"
    <?= $minlength ? "minlength='$minlength'" : '' ?>
    <?= $maxlength ? "maxlength='$maxlength'" : '' ?>
    <?= $pattern ? "pattern='$pattern'" : '' ?>
    title="<?= $validacion ?>"
    rows="1"><?= $value ?></textarea>
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
