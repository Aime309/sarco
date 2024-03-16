
<!DOCTYPE html>
<html lang="es">
<head>
<?php include '../partials/header.php' ?>
<?php include '../menu.php';?>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar salas de estudiantes</title>
</head>
<body>
<style>
        .container {
            width: 40%;
            margin: 0 auto;
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 4px;
        }

        input[type="text"], input[type="number"], select {
            width: 90%;
            padding: 8px;
            border-radius: 2px;
            border: 1px solid #ccc;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
            margin-bottom: 9px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
    <div class="container">
        <h1>Modificar salas de estudiantes</h1>
        <form action="/update-rooms" method="post">
            <label for="student-id">ID de estudiante:</label>
            <input type="number" id="student-id" name="student-id" required>
            <br>
            <label for="new-room">Nueva sala:</label>
            <select id="new-room" name="new-room" required>
                <option value="1">Sala 1</option>
                <option value="2">Sala 2</option>
                <option value="3">Sala 3</option>
            </select>
            <br>
            <label for="new-teacher">Nuevo maestro:</label>
            <input type="text" id="new-teacher" name="new-teacher" required>
            <br>
            <label for="new-students">Nueva cantidad de alumnos:</label>
            <input type="number" id="new-students" name="new-students" required>
            <br>
            <button type="submit">Actualizar sala</button>
        </form>
        <p>¿Quieres volver al <a href="/">inicio</a>?</p>
    </div>
</body>
</html>