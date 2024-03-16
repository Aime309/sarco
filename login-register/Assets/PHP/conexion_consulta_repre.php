<?php

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'sarco');

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar representante según la cédula
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'];
    $sql = "SELECT * FROM representantes WHERE cedula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $representante = $result->fetch_assoc();
        echo "<h1>Datos del representante:</h1>";
        echo "<p>Nombre: " . $representante['nombre'] . "</p>";
        echo "<p>Apellido: " . $representante['apellido'] . "</p>";
        echo "<p>Cédula: " . $representante['cedula'] . "</p>";
        echo "<p>Correo electrónico: " . $representante['correo_electronico'] . "</p>";
    } else {
        echo "No se encontró ningún representante con esa cédula.";
    }

    $stmt->close();
}

$conn->close();

?>