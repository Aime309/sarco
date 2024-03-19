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
  cedula int not null unique,
  clave text not null,
  id_rol INT not null,
  created_at datetime not null default current_timestamp,
  updated_at datetime not null default current_timestamp,

  foreign key(id_rol) references roles(id),
  UNIQUE(nombre, apellido)
);

DROP TABLE IF EXISTS representantes;
CREATE TABLE representantes(
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  cedula INTEGER NOT NULL UNIQUE,
  nombres VARCHAR(50) NOT NULL,
  apellidos VARCHAR(50) NOT NULL,
  sexo ENUM('Masculino', 'Femenino') NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  telefono VARCHAR(16) NOT NULL UNIQUE,
  correo VARCHAR(50) NOT NULL UNIQUE,
  direccion VARCHAR(100) NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,

  UNIQUE(nombres, apellidos)
);

/*----------  Registros preinstalados  ----------*/
INSERT INTO roles (id, nombre)
VALUES (1, 'Director(a)'), (2, 'Secretario(a)'), (3, 'Maestro(a)');
