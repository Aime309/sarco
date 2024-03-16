<!DOCTYPE html>
<html lang="es">
<head>
<?php include '../partials/header.php' ?>
<?php include '../menu.php';?>
<p>&nbsp;</p>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar momentos cursados</title>
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
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        input[type="button"] {
            background-color: #f44336;
            color: white;
            cursor: pointer;
        }

        input[type="button"]:hover {
            background-color: #da190b;
        }

        
    </style>
</head>
<body>
    <div class="container">
        <h1>Consultar momentos cursados</h1>
        <form action="/consult-hours" method="post">
            <label for="student-id">ID de estudiante:</label>
            <input type="number" id="student-id" name="student-id" required>
            <br>
            <label for="years">Años:</label>
            <select id="years" name="years[]" multiple required>
                <!-- Agrega los años desde el 2000 hasta el 3000 -->
                <script>
                    var select = document.getElementById("years");
                    for (var i = 2000; i <= 3000; i++) {
                        var option = document.createElement("option");
                        option.value = i;
                        option.text = i;
                        select.add(option);
                    }
                </script>
            </select>
            <br>
            <input type="submit" value="Consultar">
            <input type="button" value="Cancelar" onclick="window.location.href='/'">
        </form>
    </div>
</body>
</html>