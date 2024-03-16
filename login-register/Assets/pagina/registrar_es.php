<!DOCTYPE html>
<html lang="es">
<?php 

include '../menu.php';
?>


<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de registro de alumnos</title>
    
    


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

        .my-button {
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 15px 14px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
		}
	
    </style>
</head>
<body>

    <!-- agregamos el contenedor principal -->
    <div class="container">
        <div class="form-container">
            <form>
                <div class="form-field">
                    <label for="nombre">Nombre del estudiante:</label>
                    <input type="text" id="nomb_est" name="nomb_est" required>
                </div>
                <div class="form-field">
                    <label for="apellido">Apellido del estudiante:</label>
                    <input type="text" id="apell_est" name="apell_est" required>
                </div>
                <div class="form-field">
                    <label for="genero">Género del estudiante:</label>
                    <input type="text" id="gen_est" name="gen_est" required>
                       
                        
                    </select>
                </div>
                <div class="form-field">
                    <label for="fecha_nacimiento">Fecha de nacimiento del estudiante:</label>
                    <input type="date" id="f_n_est" name="fecha_nacimiento" required>
                </div>
                <div class="form-field">
                    <label for="acta_nacimiento">Acta de nacimiento del estudiante:</label>
                    <input type="file" id="act_nan_est" name="act_nan_est">
                </div>
                <div class="form-field">
                    <label for="cedula_representante">Cédula de representante:</label>
                    <input type="text" id="ci_repre" name="ci_repre" required>
                    <button type="submit" name="submit">Registrar</button>
                    <button type="submit" name="submit">Cancelar</button>
                </form>
                <form action="php/registro_usuario_be.php">
                </div>
            </form>
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

