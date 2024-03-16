<!DOCTYPE html>
<html lang="es">
<head>
<?php include '../partials/header.php' ?>
<?php include '../menu.php';?>
<p>&nbsp;</p>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar momentos</title>
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

        select, input[type="submit"], input[type="button"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #f44336;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #da190b;
        }

        input[type="button"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="button"]:hover {
            background-color: #3e8e41;
        }

        
    </style>
</head>
<body>
    <div class="container">
        <h1>Eliminar momentos</h1>
        <form action="/delete-moments" method="post">
            <label for="moment-id">ID de momento:</label>
            <input type="number" id="moment-id" name="moment-id" required>
            <br>
            <input type="submit" value="Eliminar">
            <input type="button" value="Cancelar" onclick="window.location.href='/'">
        </form>
    </div>
</body>
</html>