<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
        font-family: Arial, sans-serif;
      }
      .container {
        width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
      }
      .form-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
      }
      label {
        display: block;
        margin-top: 10px;
        width: 48%;
      }
      input[type="text"], input[type="number"], input[type="date"] {
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
      }
      input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        margin-top: 20px;
        width: 48%;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <form action="/submit-teacher" method="post">
        <div class="form-container">
          <label for="nombre">Nombre:</label>
          <input type="text" id="nombre" name="nombre" required>

          <label for="apellido">Apellido:</label>
          <input type="text" id="apellido" name="apellido" required>

          <label for="cedula">Cédula:</label>
          <input type="number" id="cedula" name="cedula" min="1" max="99999999" required>

          <label for="telefono">Teléfono:</label>
          <input type="tel" id="telefono" name="telefono" required>

          <label for="correo">Correo:</label>
          <input type="email" id="correo" name="correo" required>

          <label for="cargo">Cargo:</label>
          <input type="text" id="cargo" name="cargo" required>

          <label for="fecha_nacimiento">Fecha de nacimiento:</label>
          <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

          <input type="submit" value="Registrar">
        </div>
      </form>
    </div>
  </body>
</html>
