<?php

/**
 * @var array<int, array{
 *   value: string,
 *   children: string,
 *   selected?: bool
 * }> $opciones
 */

$opciones ??= [];
assert(is_string($validacion));
assert(is_string($name));
assert(is_string($placeholder));

$required = isset($required) ? (bool) $required : true;
$class = isset($class) ? (string) $class : null;
$children = isset($children) ? (string) $children : null;
$onchange = isset($onchange) ? (string) $onchange : null;
$value = isset($value) ? (string) $value : null;

?>

<label
  class="select-group input-group input-group--with-validation <?= $class ?>"
  data-validate="<?= $validacion ?>">
  <select
    onchange="<?= $onchange ?>"
    <?= $required ? 'required' : '' ?>
    class="input-group__input"
    name="<?= $name ?>"
    placeholder="<?= $placeholder ?>">
    <option data-type="placeholder" selected disabled>Selecciona una opción</option>
    <?php foreach ($opciones as $opcion) : ?>
      <option
        <?= @$opcion['selected'] || @$opcion['value'] === $value ? 'selected' : '' ?>
        value="<?= $opcion['value'] ?>">
        <?= $opcion['children'] ?>
      </option>
    <?php endforeach ?>
    <?= $children ?>
  </select>
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
