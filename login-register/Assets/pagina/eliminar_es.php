<!DOCTYPE html>
<html lang="es">
<head>
<?php 

include '../menu.php';

?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>


  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Estudiante</title>
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
    <h1>Lista de Estudiantes</h1>
    <table id="studentsTable">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Edad</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí se agregarán los estudiantes -->
      </tbody>
    </table>

    <h2>Eliminar Estudiante</h2>
    <form id="deleteStudentForm">
      <label for="id">ID del estudiante:</label>
      <input type="number" id="id" name="id" required>
      <br>
      <button type="submit">Eliminar</button>
    </form>

    <div id="message" class="message"></div>
  </div>

  <script>
    // Array de estudiantes
    const students = [];

    // Función para agregar estudiantes a la tabla
    function addStudentsToTable(students) {
      const tableBody = document.getElementById('studentsTable').getElementsByTagName('tbody')[0];
      tableBody.innerHTML = '';
      students.forEach(student => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${student.nombre}</td>
          <td>${student.apellido}</td>
          <td>${student.edad}</td>
          <td><button onclick="modifyStudent(${student.id})">Modificar</button></td>
        `;
        tableBody.appendChild(row);
      });
    }

    // Función para eliminar un estudiante
    function deleteStudent(id) {
      const index = students.findIndex(student => student.id === id);
      if (index !== -1) {
        students.splice(index, 1);
        addStudentsToTable(students);
        const message = document.getElementById('message');
        message.textContent = 'Estudiante eliminado exitosamente.';
        message.style.display = 'block';
        setTimeout(() => {
          message.style.display = 'none';
        }, 3000);
      }
    }

    // Agregar estudiantes a la tabla
    addStudentsToTable(students);

    // Función para manejar el envío del formulario de eliminación
    function handleDeleteStudentFormSubmit(event) {
      event.preventDefault();
      const id = document.getElementById('id').value;
      deleteStudent(id);
      document.getElementById('id').value = '';
    }

    // Agregar el evento de envío al formulario de eliminación
    const deleteStudentForm = document.getElementById('deleteStudentForm');
    deleteStudentForm.addEventListener('submit', handleDeleteStudentFormSubmit);
  </script>
</body>
</html>