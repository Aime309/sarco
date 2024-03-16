
<!DOCTYPE html>
<html lang="es">
<head>
    <?php 

include '../menu.php';
?>
 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modificar Estudiante</title>
  <style>
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      border: 1px solid #ccc;
      padding: 20px;
      margin: 20px auto;
      max-width: 400px;
    }

    
  </style>
</head>
<body>
  <div class="container">
    <h1>Lista de Estudiantes</h1>
    <table id="studentsTable">
      <thead>
        <tr>
          <th>Nombre,</th>
          <th>Apellido,</th>
          <th>Edad,</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí se agregarán los estudiantes -->
      </tbody>
    </table>

    <h2>Agregar Estudiante</h2>
    <form id="addStudentForm">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required>
      <br>
      <label for="apellido">Apellido:</label>
      <input type="text" id="apellido" name="apellido" required>
      <br>
      <label for="edad">Edad:</label>
      <input type="number" id="edad" name="edad" required>
      <br>
      <button type="submit">Agregar</button>
    </form>

    <h2>Modificar Estudiante</h2>
    <form id="modifyStudentForm">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required>
      <br>
      <label for="apellido">Apellido:</label>
      <input type="text" id="apellido" name="apellido" required>
      <br>
      <label for="edad">Edad:</label>
      <input type="number" id="edad" name="edad" required>
      <br>
      <button type="submit">Modificar</button>
    </form>
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

    // Función para agregar un estudiante
    function addStudent(student) {
      students.push(student);
      addStudentsToTable(students);
    }

    // Función para modificar un estudiante
    function modifyStudent(id) {
      const student = students.find(student => student.id === id);
      const index = students.indexOf(student);
      document.getElementById('nombre').value = student.nombre;
      document.getElementById('apellido').value = student.apellido;
      document.getElementById('edad').value = student.edad;
      document.getElementById('modifyStudentForm').onsubmit = () => {student.nombre = document.getElementById('nombre').value;
        student.apellido = document.getElementById('apellido').value;
        student.edad = document.getElementById('edad').value;
        students[index] = student;
        addStudentsToTable(students); // Actualizar la tabla
        return false; // Evitar que la página se recargue
      };
    }

    // Agregar un estudiante de ejemplo a la tabla
    addStudent({ id: 1, nombre: '', apellido: '', edad: '' });
  </script>
</body>
</html>