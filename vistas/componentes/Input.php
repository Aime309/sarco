<?php

if (!enum_exists(InputType::class)) {
  enum InputType: string {
    case Text = 'text';
    case Number = 'number';
    case Password = 'password';
    case Date = 'date';
    case Month = 'month';
    case Tel = 'tel';
    case Email = 'email';
    case Search = 'search';
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
$readonly = isset($readonly) ? (bool) $readonly : false;
$disabled = isset($disabled) ? (bool) $disabled : false;
$class = isset($class) ? (string) $class : null;
$list = isset($list) ? (string) $list : null;
$onchange = isset($onchange) ? (string) $onchange : null;
$onblur = isset($onblur) ? (string) $onblur : null;
$onkeydown = isset($onkeydown) ? (string) $onkeydown : null;
$onkeyup = isset($onkeyup) ? (string) $onkeyup : null;

$type = (isset($type) and is_string($type))
  ? InputType::from($type)
  : InputType::Text;

$id = uniqid('input-password-');

?>

<label
  class="input-group input-group--with-validation <?= $disabled ? 'input-group--disabled' : '' ?> <?= $required ? 'input-group--required' : 'input-group--optional' ?> <?= $class ?>"
  data-validate="<?= $validacion ?>">
  <input
    list="<?= $list ?>"
    <?= $readonly ? 'readonly' : '' ?>
    <?= $disabled ? 'disabled' : '' ?>
    type="<?= $type->value ?>"
    <?= $required ? 'required' : '' ?>
    class="input-group__input"
    name="<?= $name ?>"
    placeholder="<?= $placeholder ?>"
    value="<?= $value ?>"
    onchange="<?= $onchange ?>"
    onblur="<?= $onblur ?>"
    onkeydown="<?= $onkeydown ?>"
    onkeyup="<?= $onkeyup ?>"
    <?= $min !== null ? "min='$min'" : '' ?>
    <?= $max ? "max='$max'" : '' ?>
    <?= $minlength ? "minlength='$minlength'" : '' ?>
    <?= $maxlength ? "maxlength='$maxlength'" : '' ?>
    <?= $pattern ? "pattern='$pattern'" : '' ?>
    title='<?= $validacion ?>'
  />
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>

<?php if ($type === InputType::Password): ?>
  <div class="form-check px-4 mb-4 mostrar-clave" style="user-select: none">
    <input class="form-check-input" type="checkbox" id="<?= $id ?>">
    <label for="<?= $id ?>" style="display: inline-block">
      Mostrar contraseña
    </label>
  </div>
<?php endif ?>
