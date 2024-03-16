<!DOCTYPE html>
<html lang="es">
<head>
<?php include '../menu.php';?>
<p>&nbsp;</p>
<p>&nbsp;</p>
 <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Representante</title>
  <style>
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #ccc;
      padding: 20px;
      margin: 20px auto;
      max-width: 500px;
    }

    .message {
      margin-top: 20px;
      background-color: #dff0d8;
      padding: 10px;
      border: 1px solid #3c763d;
      border-radius: 5px;
      color: #3c763d;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Lista de Representante</h1>
    <table id="studentsTable">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Edad</th>
          <th>Cedula</th>
         <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí se agregarán los representantes -->
      </tbody>
    </table>

    <h2>Eliminar Representantes</h2>
    <form id="deleterepresentForm">
      <label for="id">ID del Representante:</label>
      <input type="number" id="id" name="id" required>
      <br>
      <button type="submit">Eliminar</button>
    </form>

    <div id="message" class="message"></div>
  </div>

  <script>
    // Array de representante
    const students = [];

    // Función para agregar representante a la tabla
    function addrepresentToTable(students) {
      const tableBody = document.getElementById('representTable').getElementsByTagName('tbody')[0];
      tableBody.innerHTML = '';
      represent.forEach(represent => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${represent.nombre}</td>
          <td>${represent.apellido}</td>
          <td>${represent.edad}</td>
          <td><button onclick="modifyStudent(${represent.id})">Modificar</button></td>
        `;
        tableBody.appendChild(row);
      });
    }

    // Función para eliminar un estudiante
    function deleteStudent(id) {
      const index = students.findIndex(student => student.id === id);
      if (index !== -1) {
        represent.splice(index, 1);
        addrepresentToTable(represent);
        const message = document.getElementById('message');
        message.textContent = 'representante eliminado exitosamente.';
        message.style.display = 'block';
        setTimeout(() => {
          message.style.display = 'none';
        }, 3000);
      
    

    // Agregar representante a la tabla
    addStudentsToTable(students);

    // Función para manejar el envío del formulario de eliminación
    function handleDeleterepresentFormSubmit(event) {
      event.preventDefault();
      const id = document.getElementById('id').value;
      deleterepresent(id);
      document.getElementById('id').value = '';
    }

    // Agregar el evento de envío al formulario de eliminación
    const deleterepresentForm = document.getElementById('deleteStudentForm');
    deleterepresentForm.addEventListener('submit', handleDeleterepresentFormSubmit);
  </script>
</body>
</html>