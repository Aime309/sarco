<!DOCTYPE html>
<html>
  <head>
  <?php 

include '../menu.php';
?>
    <meta charset="UTF-8" />
    <title>Formulario de asignación de salas</title>
    <style>
      .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        border: 2px solid #ccc;
        border-radius: 5px;
        width: 50%;
        margin:  auto;
      }
      body {
        margin: 10px;
      }
      h1 {
        margin-top: 0px;
      }
      label {
        display: block;
        margin: auto;
      }
      select,
      input[type="number"] {
        width: 100%;
        padding: 5px;
        margin-top: 0px;
        
      }
      input[type="submit"] {
        margin: 0px;
      }
    </style>
  </head>
  <body>
    <div class="container">
     
      <h1>asignación de salas</h1>
      <form action="#" method="post">
        <label for="classroom">Seleccione una sala:</label>
        <select id="classroom" name="classroom" required>
          <option value="">Seleccione una opción</option>
          <option value="1">Sala 1 - Maestro A - 30 alumnos</option>
          <option value="2">Sala 2 - Maestra B - 25 alumnos</option>
          <option value="3">Sala 3 - Maestro C - 20 alumnos</option>
        </select>

        <label for="num_students">Número de alumnos:</label>
        <input type="number" id="num_students" name="num_students" min="1" max="50" required />

        <input type="submit" value="Asignar" />
      </form>
    </div>
  </body>
</html>
    

     