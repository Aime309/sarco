<?php 

include '../menu.php';
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
    <title>Búsqueda de representantes</title>
    <style>
        .form-container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin: 50px auto; /* Agregar margen superior */
        }
        .form-field {
            margin-bottom: 10px;
        }
        .form-field label {
            display: absolute;
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
        <form action="php/conexion_consulta_repre.php" method="post">
            <div class="form-field">
                <label for="cedula">Cédula:</label>
                <input type="text" name="cedula" id="cedula" required>
            </div>
            <div class="form-button">
                <input type="submit" value="Buscar">
            </div>
        </form>
    </div>
</body>
</html>