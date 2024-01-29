CREATE DATABASE IF NOT EXISTS sarco_proyect;
USE sarco_proyect;

DROP TABLE IF EXISTS roles;
CREATE TABLE roles(
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre varchar(20) not null unique
);

DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios(
  id int PRIMARY KEY AUTO_INCREMENT,
  nombre varchar(20) not null,
  apellido varchar(20) not null,
  clave text not null,
  id_rol INT not null,
  fecha_creacion datetime not null default current_timestamp,

  foreign key(id_rol) references roles(id),
  UNIQUE(nombre, apellido)
);

DROP TABLE IF EXISTS maestros;
CREATE TABLE maestros(
  id int PRIMARY KEY AUTO_INCREMENT,
  nombre varchar(20) not null,
  apellido varchar(20) not null,
  fecha_nacimiento date not null,
  telefono varchar(16),
  direccion text,
  correo_electronico varchar(100) unique,
  acta_nacimiento varchar(200) not null unique,
  curriculum varchar(200) not null unique,
  fecha_creacion datetime not null default current_timestamp,

  UNIQUE(nombre, apellido)
);

DROP TABLE IF EXISTS estudiantes;
CREATE TABLE estudiantes(
id int PRIMARY KEY AUTO_INCREMENT,
cod_est
nombre
apellido
fecha_nacimiento
genero
act_nacimiento
fecha
);

-- INSERT INTO roles (nombre)
-- VALUES ('Director(a)'), ('Secretario(a)'), ('Maestro(a)');

-- INSERT INTO usuarios (nombre, apellido, clave, id_rol)
-- VALUES ('Franyer', 'Sánchez', '$2y$10$ONbhQhaPVZAoUO55Id8xXuaIXDt0AHJ8kmvZ9a/acza30Th5qM6qy', 3);
