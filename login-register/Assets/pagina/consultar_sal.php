<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../partials/header.php' ?>
    <?php include '../menu.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar salas</title>
    <style>
    <style>
        body {
      font-family: Arial, sans-serif;
      }
        .container {
              max-width: 700px;
               margin: 0 auto;
                display: flex; 
                flex-direction: column;
                 align-items: center;
                }
        .form-container { 
             display: flex; 
             flex-direction: column; 
             align-items: center;
              background-color: #fff;
               padding: 20px;
                border-radius: 5px; 
                width: 50%;
                 box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
        h1 {
              text-align: center;
               margin-bottom: 30px;
           }
        form {
              display: flex;
               flex-direction: column;
                margin-bottom: 20px;
                }
        label {
              margin-bottom: 5px;
           }
        input[type="text"], input[type="submit"] { padding: 5px; margin-bottom: 10px; border-radius: 5px; border: 2px solid #ccc; }
        input[type="submit"] { cursor: pointer; background-color: #4CAF50; color: black; }
        #resultado { border:1px solid #ccc; padding: 20px; border-radius: 10px; margin-bottom: 2px; overflow-y: auto; }
    
    </style>

</head>
<body>
    <div class="container">
        <h1>Consultar Salas de Estudiantes</h1>
        <div class="form-container">
            <form id="form">
                <label for="id">ID de la Sala:</label><br>
                <input type="text" id="id" name="id" required readonly><br>
                <input type="submit" value="Consultar">
            </form>
       </div>
        <div id="resultado"></div>
    </div>
    <script>
        const form = document.querySelector('#form');
        const resultado = document.querySelector('#resultado');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.querySelector('#id').value;

            try {
                const response = await fetch('/api/salas/estudiantes/consultar', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });

                const data = await response.json();

                if (data.success) {
                    let html = '<h2>Sala: ' + data.sala.nombre + '</h2>';
                    html += '<table border="1"><tr>
                        <th>Maestro</th>
                        <th>Estudiantes</th>
                    </tr>';

                    data.sala.maestros.forEach((maestro) => {
                        html += '<tr>
                            <td>' + maestro.nombre + '</td>
                            <td>';

                        maestro.estudiantes.forEach((estudiante) => {
                            html += estudiante.nombre + ' ';
                        });

                        html += '</td>
                    </tr>';
                    });

                    html += '</table>';
                    resultado.innerHTML = html;
                    resultado.style.height = 'auto';
                } else {
                    resultado.innerHTML = 'Error al consultar la sala.';
                }
            } catch (error) {
                resultado.innerHTML = 'Error al consultar la sala.';
            }
        });
    </script>
</body>
</html>