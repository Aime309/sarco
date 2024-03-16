<?php 

    class Database{
    	//SE crean variables privadas que me permitira hacer la conexion con la base de datos
        private $servername; //nombre del servidor
        private $user; //el usuario dentro del servidor en nuestro el que tiene por defecto phpmyadmin
        private $password; //password en caso tal que se haya una
        private $dbname; //nombre de la base de datos

// se crea la funcion constructora en el que se le asigana los valores a cada variable
        public function __construct(){
            $this->servername = 'localhost';
            $this->user = 'root';
            $this->password = '';
            $this->dbname = 'sarco';
        }
//Declaramos la funcion que nos permitira hacer la conexion con la base de datos
        public function connect(){
            $conn = new mysqli($this->servername,$this->user,$this->password,$this->dbname);
            return $conn;
        }


    }