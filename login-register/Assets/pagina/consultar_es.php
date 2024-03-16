<!DOCTYPE html>
<html lang="es">
<head>
<?php include '../partials/header.php' ?>
<?php include '../menu.php';?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de estudiantes</title>
    <style>
        .form-container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin: 0 auto;
        }
        .form-field {
            margin-bottom: 10px;
        }
        .form-field label {
            display: block;
            margin-bottom: 5px;
        }
        .form-field input[type="text"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-button {
            margin-top: 10px;
        }

        
    </style>
</head>
<body>
    <div class="form-container">
        <form action="search_student.php" method="post">
            <div class="form-field">
                <label for="cedula_representante">Cédula del representante:</label>
                <input type="text" name="cedula_representante" id="cedula_representante" required>
            </div>
            <div class="form-button">
                <input type="submit" value="Buscar">
            </div>
           </form>
    </div>
    </div>
            <div class="form-button">
                <input type="submit" value="Buscar">
            </div>
            </div>
    <button class="my-button" onclick="atras()">Atras</button>

<script>
	function atras() {
		window.location.href = "../welcome.php";
	}
    </script>
</body>
</html>


