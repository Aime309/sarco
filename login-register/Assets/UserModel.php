<?php 

//creamos una nueva clase que hereda atributos de otra clase llamada Database
    class User extends Database{
//Creamos una funcion que en este caso va a obtener un parametro que es el usuario y la clave
        public function getUser($username, $password){
            //se crea una consulta con SELECT * FROM, que me permitira verificar si en la bbdd se encuentra la informacion segun el parametro establecido
            $sql = "SELECT * FROM usuarios WHERE usuario = '$username' AND contrasena ='$password'";
//se crea una variable $result que permite hacer la conexion con la bbdd y ejecutar la consulta $sql de la linea anterior
            $result = $this->connect()->query($sql);
//la linea siguiente nos arroja el numero de filas segun la consulta realizada que este caso solo es 1
            $numRows = $result->num_rows;
            if($numRows > 0){
                
                $rol2 = array();
                while($row = @mysqli_fetch_array($result)){
                $rol2 = $row[5];
               //echo $rol2;
                
                }
              if ($rol2=="A") { 
                 header('Location: welcome.php');
             
             } else if ($rol2=="U") {
                header('Location: welcome2.php');
              }
                
             return $rol2; 
            } 
          return false;   
        }

    }