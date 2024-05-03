<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
        font-family: Arial, sans-serif;
      }
      .container {
        width: 400px;
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
      textarea {
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
        height: 100px;
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
      <form action="/submit-observation" method="post">
        <div class="form-container">
          <label for="nombres">Nombres y apellidos:</label>
          <input type="text" id="nombres" name="nombres" required>

          <label for="edad">Edad:</label>
          <input type="number" id="edad" name="edad" min="1" max="100" required>

          <label for="cedula">Cédula escolar:</label>
          <input type="text" id="cedula" name="cedula" required>

          <label for="representante">Representante:</label>
          <input type="text" id="representante" name="representante" required>

          <label for="docente">Docente:</label>
          <input type="text" id="docente" name="docente" required>

          <label for="inasistencia">Nombre de inasistencia del niño:</label>
          <input type="text" id="inasistencia" name="inasistencia">

          <label for="proyecto">Proyecto de aprendizaje:</label>
          <input type="text" id="proyecto" name="proyecto" required>

          <label for="personal">Breve descripción formulario personal,social y comunicacion:</label>
          <textarea id="personal" name="personal"></textarea>

          <label for="ambiente">Breve descripción relación entre los componentes del ambiente:</label>
          <textarea id="ambiente" name="ambiente"></textarea>

          <label for="recomendaciones">Recomendaciones al representante:</label>
          <textarea id="recomendaciones" name="recomendaciones"></textarea>

          <input type="submit" value="Registrar">
        </div>
      </form>
    </div>
  </body>
</html>
