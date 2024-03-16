<?php 

//verificamos que exista el submit y guardamos los valores del formulario en variables
    if(isset($_POST['submit'])){
        $username = $_POST['usuario'];
        $password = $_POST['contrasena'];
//comprobamos que los campos usuario y clave no este vacio
        if(empty($username) || empty($password)){
            echo '<div class="alert alert-danger">Nombre de usuario o contraseña vacio</div>';
        }else{
            $user = new User;

            if($user->getUser($username,$password)){
               session_start();
                $_SESSION['usuario'] = $username;
               //header('Location: welcome.php and welcome2.php');

            }
        }

    }

?>