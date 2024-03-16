<?php

    include 'conexion_be.php';
    $usuario = $_POST ['usuario']; 
    $contrasena = $_POST ['contrasena']; 


    $validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario'
    and contrasena = '$contrasena'");

    if(mysqli_num_rows($validar_login)> 0 ){
        echo'
        <script>
        alert( "Estas Dentro");
        window.location = "bienvenido.php";
        </script>;
        ';
        exit;

    }else{
        echo'
        <script>
        alert( "Usuario o Contraseña Incorrecta");
        window.location = "../index.php";
        </script>;
        ';
    }
    exit();



?>