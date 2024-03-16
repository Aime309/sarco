<!DOCTYPE html>
<html lang="es">

<head>
  <title>SARCO | Ingreso</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="assets/images/icons/favicon.ico" />
  <link rel="stylesheet" href="assets/css/reset.css" />
  <link rel="stylesheet" href="assets/css/button.css" />
  <link rel="stylesheet" href="assets/css/form.css" />
  <link rel="stylesheet" href="assets/css/responsive.css" />
</head>

<body>
  <form method="post" action="./ingresar" class="form form--full form--with-validation">
    <div class="form__background"></div>
    <div class="form__body">
      <h1 class="form__title">Inicia sesión para continuar</h1>
      <label class="input-group input-group--with-validation" data-validate="La cédula es requerida">
        <input class="input-group__input" type="number" min="0" name="cedula" placeholder="Cédula" />
        <span class="input-group__focus"></span>
        <span class="input-group__label">Cédula</span>
      </label>
      <label class="input-group input-group--with-validation" data-validate="La contraseña es requerida">
        <input class="input-group__input" type="password" name="clave" placeholder="Contraseña" />
        <span class="input-group__focus"></span>
        <span class="input-group__label">Contraseña</span>
      </label>
      <div class="form__remember">
        <label class="checkbox">
          <input class="checkbox__input" type="checkbox" />
          <span class="checkbox__label">Recuérdame</span>
        </label>
        <a href="#">
          ¿Olvidó su contraseña?
        </a>
      </div>
      <button class="button">Ingresar</button>
    </div>
  </form>
  <script type="module" src="assets/js/login.js"></script>
</body>

</html>
