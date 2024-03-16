<!DOCTYPE html>
<html>
  <head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de registro de representantes</title>
    
    <?php include '../menu.php';?>


    <style>

        
        .form-container {
            width: 300px;
            padding: 20px;
            border: 1px solid ;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin: 0 auto; /* eliminamos el margin auto */
        }
        .form-field {
            margin-bottom: 10px;
        }
        .form-field label {
            display: block;
            margin-bottom: 5px;
        }
        .form-field input[type="text"], .form-field input[type="date"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-field select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-field input[type="file"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-button {
            margin-top: 10px;
        }
        /* agregamos el estilo para el contenedor principal */
        .container {
            
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 0;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- agregamos el contenedor principal -->
    <div class="container">
        <div class="form-container">
            <form>
                <div class="form-field">
                    <label for="nombre">Nombre del Representante:</label>
                    <input type="text" id="nomb_res" name="nomb_res" required>
                </div>
                <div class="form-field">
                    <label for="apellido">Apellido del Representante:</label>
                    <input type="text" id="apell_res" name="apell_res" required>
                </div>
                <div class="form-field">
                    <label for="genero">Género del Representante:</label>
                    <select id="genero" name="genero" required>
                        <option value="">Selecciona una opción</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="fecha_nacimiento">Fecha de nacimiento del Representante:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="form-field">
                    <label for="direccion_representante">direccion:</label>
                    <input type="text" id="acta_nacimiento" name="acta_nacimiento" required>
                </div>
                <div class="form-field">
                    <label for="cedula_representante">Cédula de representante:</label>
                    <input type="text" id="cedula_representante" name="cedula_representante" required>
                </div>
                <div class="form-button">
                    <button type="submit">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>