<!DOCTYPE html>
<html>
  <head>

  <?php 

include '../menu.php';
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de registro de representantes</title>
    
    


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
                    <label for="nombre">Nombre del Maestro:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-field">
                    <label for="apellido">Apellido del Maestro:</label>
                    <input type="text" id="apellido" name="apellido" required>
                </div>
                <div class="form-field">
                    <label for="genero">Género del Maestro:</label>
                    <select id="genero" name="genero" required>
                        <option value="">Selecciona una opción</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="fecha_nacimiento">Fecha de nacimiento del Maestro:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="form-field">
                    <label for="direccion_maestro">direccion:</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>
                <div class="form-field">
                    <label for="cedula_maestro">Cédula de Maestro:</label>
                    <input type="text" id="cedula_maestro" name="cedula_maestro" required>
                </div>
               <label for="phone">Teléfono:</label>
            <input type="tel" id="phone" name="phone" required />
                <div class="form-button">
                    <button type="submit">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>