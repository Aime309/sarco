<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "maestros_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cedula = $_POST['cedula'];

$sql = "DELETE FROM maestros WHERE nombre = '$nombre' AND apellido = '$apellido' AND cedula = '$cedula'";

if ($conn->query($sql) === TRUE) {
    echo "Maestro eliminado correctamente.";
} else {
    echo "Error al eliminar el maestro: " . $conn->error;
}

$conn->close();
?>