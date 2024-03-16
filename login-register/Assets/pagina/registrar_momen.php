 <!DOCTYPE html>
<html lang="en">
  <head>
  <?php 

include '../menu.php';
?>
    
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de momentos</title>
    <style>
      .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 5px;
        width: 50%;
        margin: 0 auto;
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
      input[type="text"],
      input[type="date"],
      textarea {
        width: 100%;
        padding: 10px;
        margin-top: 0px;
      }
      input[type="submit"] {
        margin-top: 0%;
      }
      textarea {
        word-wrap: break-word;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>registro de momentos</h1>
      <form action="#" method="post">
        <label for="title">Título:</label>
        <input type="text" id="title" name="title" required />

        <label for="description">Descripción:</label>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea>

        <label for="date">Fecha:</label>
        <input type="date" id="date" name="date" required />

        <input type="submit" value="Registrar" />
      </form>
    </div>
  </body>
</html>