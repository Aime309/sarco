<!DOCTYPE html>
<html>
<head>
  <title>Formulario de datos del estudiante</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      width: 300px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f2f2f2;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-top: 10px;
    }

    input[type="text"], input[type="date"], select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      font-size: 16px;
    }

    input[type="radio"] {
      margin-top: 5px;
    }

    .representative-container {
      position: relative;
      margin-top: 10px;
    }

    .representative-search {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      font-size:

        .representative-search {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      font-size: 16px;
    }

    .representative-search:focus {
      outline: none;
      box-shadow: 0px 0px 3px rgba(0,0,0,0.2);
    }

    .submit-button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 20px;
    }

    .submit-button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <form>
      <label for="student-id">Cédula Escolar:</label>
      <input type="text" id="student-id" name="student-id">

      <label for="full-name">Nombre Completo:</label>
      <input type="text" id="full-name" name="full-name">

      <label for="last-name">Apellido Completo:</label>
      <input type="text" id="last-name" name="last-name">

      <label for="gender">Sexo:</label>
            <input type="radio" id="male" name="gender" value="masculino">
      <label for="male">Masculino</label>
      <input type="radio" id="female" name="gender" value="femenino">
      <label for="female">Femenino</label>

      <label for="birth-date">Fecha de Nacimiento:</label>
      <input type="date" id="birth-date" name="birth-date">

      <label for="birth-place">Lugar de Nacimiento:</label>
      <input type="text" id="birth-place" name="birth-place">

      <label for="blood-type">Tipo de Sangre:</label>
      <select id="blood-type" name="blood-type">
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
      </select>

      <label for="age">Edad del Niño:</label>
      <input type="number" id="age" name="age" min="1" max="18">

      <label for="representative">Representante:</label>
      <div class="representative-container">
        <input type="text" id="representative-search" class="representative-search"
              <input type="text" id="representative-search" class="representative-search" placeholder="Buscar representante...">
      <div id="representative-list" class="representative-list">
        <!-- Representantes se cargarán aquí -->
      </div>
    </form>
  </div>

  <script>

  </script>
</body>
</html>

