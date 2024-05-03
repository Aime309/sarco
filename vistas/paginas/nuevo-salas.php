<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Registrar Salas</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        max-width: 300px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 1px rgba(0, 0, 0, 0.1);
      }

      h1 {
        text-align: center;
        margin-bottom: 20px;
      }

      label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
      }

      input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
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
    <h1>Registrar Salas</h1>
    <form action="/submit_room" method="post">
      <label for="room_name">Nombre de la Sala:</label><br />
      <input type="text" id="room_name" name="room_name" required /><br />

      <input type="submit" value="Registrar" />
    </form>
  </body>
</html>
