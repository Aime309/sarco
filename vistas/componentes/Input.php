<?php

if (!enum_exists(InputType::class)) {
  enum InputType: string {
    case Text = 'text';
    case Number = 'number';
    case Password = 'password';
    case Date = 'date';
    case Tel = 'tel';
    case Email = 'email';
  }
}

assert(is_string($validacion));
assert(is_string($name));
assert(is_string($placeholder));

$min = isset($min) ? (int) $min : null;
$max = isset($max) ? (int) $max : null;
$minlength = isset($minlength) ? (string) $minlength : null;
$maxlength = isset($maxlength) ? (string) $maxlength : null;
$value = isset($value) ? (string) $value : null;
$pattern = isset($pattern) ? (string) $pattern : null;
$required = isset($required) ? (bool) $required : true;

$type = (isset($type) and is_string($type))
  ? InputType::from($type)
  : InputType::Text;

?>

<label class="input-group input-group--with-validation" data-validate="<?= $validacion ?>">
  <input type="<?= $type->value ?>" <?= $required ? 'required' : '' ?> class="input-group__input" name="<?= $name ?>" placeholder="<?= $placeholder ?>" value="<?= $value ?>" <?= $min ? "min='$min'" : '' ?> <?= $max ? "max='$max'" : '' ?> <?= $minlength ? "minlength='$minlength'" : '' ?> <?= $maxlength ? "maxlength='$maxlength'" : '' ?> <?= $pattern ? "pattern='$pattern'" : '' ?> title='<?= $validacion ?>' />
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
