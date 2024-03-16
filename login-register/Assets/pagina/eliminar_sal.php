<!DOCTYPE html>
<html lang="es">
<head>
<?php include '../partials/header.php' ?>
<?php include '../menu.php';?>
<p>&nbsp;</p>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar sala</title>
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        input[type="submit"], input[type="button"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #f44336;
            color: white;
            cursor: pointer;
        }

        input[type="button"] {
            background-color: #ccc;
            color: #333;
            margin-top: 10px;
        }

        input[type="button"]:hover {
            background-color: #ddd;
            color: #333;
        }

        
    </style>
</head>
<body>
    <div class="container">
        <h1>Eliminar sala</h1>
        <form action="/delete-room" method="post">
            <label for="room-name">Nombre de sala:</label>
            <input type="text" id="room-name" name="room-name" required>
            <br>
            <input type="submit" value="Eliminar sala">
            <input type="button" value="Cancelar" onclick="window.location.menu.php='/'">
        </form>
    </div>
</body>
</html>