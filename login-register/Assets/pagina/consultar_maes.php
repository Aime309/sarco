<!DOCTYPE html>
<html lang="es">
<head>
<?php include '../partials/header.php' ?>
<?php include '../menu.php';?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de maestro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f2f2f2;
        }

        
    </style>
</head>
<body>
    <div class="container">
        <h1>Consulta de maestro</h1>
        <form id="consulta-form">
            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula" required>
            <button type="submit">Consultar</button>
        </form>
        <div id="result" class="result"></div>
    </div>
    <script>
        const form = document.getElementById("consulta-form");
        const cedulaInput = document.getElementById("cedula");
        const resultDiv = document.getElementById("result");

        // Mock data
        const maestros = [
            {
                cedula: "",
                nombre: "",
                apellido: "",
                genero: "",
                telefono: "",
                direccion: "",
            },
            {
                cedula: "",
                nombre: "",
                apellido: "",
                genero: "",
                telefono: "",
                direccion: ""
            }
            // Agrega más maestros aquí
        ];

        form.addEventListener("submit", (e) => {
            e.preventDefault();
            const cedula = cedulaInput.value;
            const maestro = maestros.find((m) => m.cedula === cedula);
            if (maestro) {
                resultDiv.innerHTML = `
                    <p><strong>Nombre:</strong> ${maestro.nombre} ${maestro.apellido}</p>
                    <p><strong>Cédula:</strong> ${maestro.cedula}</p>
                    <p><strong>Género:</strong> ${maestro.genero}</p>
                    <p><strong>Teléfono:</strong> ${maestro.telefono}</p>
                    <p><strong>Dirección:</strong> ${maestro.direccion}</p>
                `;
            } else {
                resultDiv.innerHTML = "<p>No se encontró ningún maestro con esa cédula.</p>";
            }
        });
    </script>
</body>
</html>