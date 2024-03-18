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
      input[type="text"], input[type="number"] {
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
      <form action="/submit-registration" method="post">
        <div class="form-container">
          <label for="nombre">Nombre del Niño:</label>
          <input type="text" id="nombre" name="nombre" required>

          <label for="edad">Edad:</label>
          <input type="number" id="edad" name="edad" min="1" max="18" required>

          <label for="sala">Sala:</label>
          <select id="sala" name="sala" required>
            <option value="">Selecciona una sala</option>
            <option value="sala1">Sala 1</option>
            <option value="sala2">Sala 2</option>
            <option value="sala3">Sala 3</option>
          </select>

          <label for="periodo">Periodo:</label>
          <input type="text" id="periodo" name="periodo" required>

          <label for="momento">Momento:</label>
          <input type="text" id="momento" name="momento" required>

          <input type="submit" value="Registrar">
        </div>
      </form>
    </div>
  </body>
</html>
