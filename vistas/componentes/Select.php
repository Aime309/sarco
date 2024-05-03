<?php

/**
 * @var array<int, array{value: string, children: string, selected?: bool}> $opciones
 */

assert(is_string($validacion));
assert(is_string($name));
assert(is_string($placeholder));
assert(is_array($opciones));

$required = isset($required) ? (bool) $required : true;

?>

<label class="select-group input-group input-group--with-validation" data-validate="<?= $validacion ?>">
  <select <?= $required ? 'required' : '' ?> class="input-group__input" name="<?= $name ?>" placeholder="<?= $placeholder ?>">
    <option selected disabled>Selecciona una opción</option>
    <?php foreach ($opciones as $opcion) : ?>
      <option <?= @$opcion['selected'] ? 'selected' : '' ?> value="<?= $opcion['value'] ?>">
        <?= $opcion['children'] ?>
      </option>
    <?php endforeach ?>
  </select>
  <span class="input-group__focus"></span>
  <span class="input-group__label"><?= $placeholder ?></span>
</label>
