<!DOCTYPE html>
<html lang="en">
<head>
<?php include '../partials/header.php' ?>
<?php include '../menu.php';?>
<p>&nbsp;</p>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Maestro</title>
    <style>
        
    
        .container {
            max-width: 700px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            width: 40%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"], 
        input[type="submit"]
         { padding: 4px; 
            margin-bottom: 5px;
             border-radius: px; 
             border: 2px solid #ccc;
             }
        input[type="submit"] 
        { cursor: pointer;
             background-color: #4CAF50;
              color: black;
             }

             
    </style>
</head>
<body>
    <div class="container">
        <h1>Eliminar Maestro</h1>
        <div class="form-container">
            <form id="form" method="post" action="eliminar_maestro.php"><label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre" required><br>
                <label for="apellido">Apellido:</label><br>
                <input type="text" id="apellido" name="apellido" required><br>
                <label for="cedula">Cédula:</label><br>
                <input type="text" id="cedula" name="cedula" required><br>
                <label for="telefono">Teléfono:</label><br>
                <input type="text" id="telefono" name="telefono" required><br>
                <label for="direccion">Dirección:</label><br>
                <input type="text" id="direccion" name="direccion" required><br>
                <input type="submit" value="Eliminar">
            </form>
       </div>
    </div>
</body>
</html>