<?php include '../partials/header.php' ?>
<?php include '../menu.php'?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Modificar Representantes</title>
    <style>
        body {
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            width: 70%;
            max-width: 50%;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            
        }
        .form-group {
            margin-bottom: 1px;
        }
        label {
            display: block;
            margin-bottom:0px;
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 5px 1px;
            border: none;
            border-radius: 4px;
           
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modificar Representantes</h1>
        <form action="/modificar-representante" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="cargo">Cargo:</label>
                <input type="text" id="cargo" name="cargo" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Modificar</button>
        </form>
    </div>
</body>
</html>