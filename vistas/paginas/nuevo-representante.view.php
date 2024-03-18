<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Registro Representante</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 1px rgba(0, 0, 0, 0.1);
      }

      h1 {
        text-align: center;
        margin-bottom: 30px;
      }

      label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
      }

      input[type="text"],
      input[type="number"],
      input[type="tel"],
      input[type="email"],
      select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        margin-bottom: 20px;
      }

      input[type="date"] {
        padding: 10px 10px 10px 30px;
      }

      input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }

      input[type="submit"]:hover {
        background-color: #45a049;
      }
    </style>
  </head>
  <body>
    <h1>Registro Representante</h1>
<form action="/submit_registration" method="post">
      <label for="fullname">Nombre Completo:</label><br />
      <input type="text" id="fullname" name="fullname" required /><br />

      <label for="lastname">Apellido Completo:</label><br />
      <input type="text" id="lastname" name="lastname" required /><br />

      <label for="cedula">Cédula:</label><br />
      <input type="text" id="cedula" name="cedula" required /><br />

      <label for="age">Edad:</label><br />
      <input type="number" id="age" name="age" min="1" max="120" required /><br />

      <label for="marital_status">Estado Civil:</label><br />
      <select id="marital_status" name="marital_status" required>
        <option value="S">Soltero(a)</option>
        <option value="C">Casado(a)</option>
        <option value="D">Divorciado(a)</option>
        <option value="V">Viudo(a)</option>
      </select><br />

      <label for="nationality">Nacionalidad:</label><br />
      <select id="nationality" name="nationality" required>
        <option value="V">Venezolano (a)</option>
        <option value="E">Extranjero (a)</option>
      </select><br />

      <label for="dob">Fecha de Nacimiento:</label><br />
      <input type="date" id="dob" name="dob" required /><br/>

      <label for="phone">Teléfono:</label><br />
      <input type="tel" id="phone" name="phone" required /><br />

      <label for="email">Correo electrónico:</label><br />
      <input type="email" id="email" name="email" required /><br />

      <inputtype="submit" value="Registrar" />
    </form>
  </body>
</html>
