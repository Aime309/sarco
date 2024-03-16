
   <!DOCTYPE html>
<html lang="en">
  <head>
  <?php 

include '../menu.php';
?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de periodos de alumnos</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 10px;
      }
      .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        border: 2px solid #ccc;
        border-radius: 5px;
        width: 300px;
        margin: 0 auto;
      }
      h1 {
        margin-top: 0px;
        margin-bottom: 10px;
      }
      label {
        display: block;
        margin-top: 5px;
        margin-bottom: 2px;
      }
      input[type="text"],
      input[type="date"],
      select {
        width: 100%;
        padding: 5px;
        margin-top: 2px;
      }
      input[type="submit"] {
        margin-top: 10px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>Registro de periodos</h1>
      <form action="#" method="post">
        <label for="student-id">ID alumno:</label>
        <input type="text" id="student-id" name="student-id" required />

        <label for="student-name">Nombre:</label>
        <input type="text" id="student-name" name="student-name" required />

        <label for="grade">Grado:</label>
        <input type="text" id="grade" name="grade" required />

        <label for="group">Grupo:</label>
        <select id="group" name="group" required>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>

        <label for="start-date">Inicio:</label>
        <input type="date" id="start-date" name="start-date" required />

        <label for="end-date">Finalización:</label>
        <input type="date" id="end-date" name="end-date" required />

        <input type="submit" value="Registrar" />
      </form>
    </div>
  </body>
</html>