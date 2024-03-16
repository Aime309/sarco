<?php
include 'conexion_be.php';
    $nombre_completo = $_POST ['nombre_completo'];
    $cedula = $_POST ['cedula'];
    $usuario = $_POST ['usuario'];
    $contrasena = $_POST ['contrasena'];

   

$query = "INSERT INTO usuarios(nombre_completo, Cedula, Usuario, contrasena) 
    VALUES('$nombre_completo', '$cedula', '$usuario', '$contrasena')";

//verificar que la cedula no se repita en la base de datos//
    $verificar_cedula = mysqli_query($conexion, "SELECT * FROM usuarios WHERE cedula = '$cedula'");

    if(mysqli_num_rows($verificar_cedula) > 0){
        echo '
        <script>
                alert("Esta cedula ya existe, intenta con otra diferente")
                window.location ="../index.php";
            </script>
        
        ';

        exit();
    }
//verificar que el cedulusuario no se repita en la base de datos//
    $verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario'");


if(mysqli_num_rows($verificar_usuario) > 0){
    echo '
    <script>
            alert(" Este Usuario ya existe, intenta con otro diferente")
            window.location ="../index.php";
        </script>
    
    ';

    exit();
}




$ejecutar = mysqli_query($conexion, $query);

    if($ejecutar){
        echo '
            <script>
                alert("Usuario Almacenado Exitosamente");
                window.location ="../index.php";
            </script>
        
        ';
    }else{
        echo '
        <script>
            alert("Intentalo de nuevo, usuario no almacenado");
            window.location ="../index.php";
        </script>
    '; 
    }

    mysqli_close($conexion);


?>