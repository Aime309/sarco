SET foreign_key_checks = 0;

DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS representantes;
DROP TABLE IF EXISTS estudiantes;
DROP TABLE IF EXISTS inscripciones;
DROP TABLE IF EXISTS asignaciones_de_docentes;
DROP TABLE IF EXISTS docentes;
DROP TABLE IF EXISTS cargos;
DROP TABLE IF EXISTS salas;
DROP TABLE IF EXISTS periodos;
DROP TABLE IF EXISTS boletines;
DROP TABLE IF EXISTS momentos;
DROP TABLE IF EXISTS asignaciones_de_salas;
DROP TABLE IF EXISTS aulas;

CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombres VARCHAR(40) NOT NULL,
  apellidos VARCHAR(40) NOT NULL,
  cedula INT NOT NULL UNIQUE,
  usuario VARCHAR(20) NOT NULL UNIQUE,
  clave TEXT NOT NULL,
  rol ENUM('Maestro/a', 'Secretario/a'),
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  esta_activo BOOL DEFAULT TRUE,

  UNIQUE (nombres, apellidos)
);

CREATE TABLE representantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombres VARCHAR(40) NOT NULL,
  apellidos VARCHAR(40) NOT NULL,
  cedula INT NOT NULL UNIQUE,
  fecha_nacimiento DATE NOT NULL,
  estado_civil ENUM('Casado/a', 'Soltero/a', 'Divorciado/a', 'Viudo/a') NOT NULL,
  nacionalidad ENUM('Venezolano/a', 'Extranjero/a') NOT NULL,
  telefono VARCHAR(16) NOT NULL UNIQUE,
  correo VARCHAR(255) NOT NULL UNIQUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,

  UNIQUE (nombres, apellidos)
);

CREATE TABLE estudiantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombres VARCHAR(40) NOT NULL,
  apellidos VARCHAR(40) NOT NULL,
  cedula_escolar VARCHAR(13) NOT NULL UNIQUE,
  fecha_nacimiento DATE NOT NULL,
  lugar_nacimiento TEXT NOT NULL,
  genero ENUM('Femenino', 'Masculino') NOT NULL,
  tipo_sangre ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
  id_mama INT NOT NULL,
  id_papa INT,

  FOREIGN KEY (id_mama) REFERENCES representantes (id),
  FOREIGN KEY (id_papa) REFERENCES representantes (id)
);

CREATE TABLE cargos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE docentes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  cedula INT NOT NULL UNIQUE,
  nombre VARCHAR(20) NOT NULL,
  apellido VARCHAR(20) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  direccion TEXT NOT NULL,
  telefono VARCHAR(16) NOT NULL UNIQUE,
  correo VARCHAR(255) NOT NULL UNIQUE,
  id_cargo INT NOT NULL,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (id_cargo) REFERENCES cargos (id)
);

CREATE TABLE salas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(20) NOT NULL UNIQUE,
  edad_minima INT NOT NULL,
  edad_maxima INT NOT NULL,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE periodos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  anio_inicio INT NOT NULL UNIQUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE asignaciones_de_docentes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_docente INT NOT NULL,
  id_sala INT NOT NULL,
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_docente) REFERENCES docentes (id),
  FOREIGN KEY (id_sala) REFERENCES salas (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE inscripciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_estudiante INT NOT NULL,
  id_asignacion_docente INT NOT NULL,
  id_asignacion_asistente INT NOT NULL,
  id_asignacion_segundo_asistente INT,

  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_asignacion_docente) REFERENCES asignaciones_de_docentes (id),
  FOREIGN KEY (id_asignacion_asistente) REFERENCES asignaciones_de_docentes (id),
  FOREIGN KEY (id_asignacion_segundo_asistente) REFERENCES asignaciones_de_docentes (id)
);

CREATE TABLE momentos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero_momento INT NOT NULL,
  mes_inicio INT NOT NULL CHECK (mes_inicio >= 1 AND mes_inicio <= 12),
  dia_inicio INT NOT NULL CHECK (dia_inicio >= 1 AND dia_inicio <= 31),
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE boletines (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_estudiante INT NOT NULL,
  id_momento INT NOT NULL,
  id_docente INT NOT NULL,
  id_asistente INT NOT NULL,
  id_segundo_asistente INT,
  numero_inasistencias INT NOT NULL CHECK (numero_inasistencias >= 0),
  nombre_proyecto VARCHAR(255) NOT NULL,
  descripcion_formacion TEXT NOT NULL,
  descripcion_ambiente TEXT NOT NULL,
  recomendaciones TEXT NOT NULL,

  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_momento) REFERENCES momentos (id),
  FOREIGN KEY (id_docente) REFERENCES docentes (id),
  FOREIGN KEY (id_asistente) REFERENCES docentes (id),
  FOREIGN KEY (id_segundo_asistente) REFERENCES docentes (id)
);

CREATE TABLE aulas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  codigo_sala VARCHAR(20) NOT NULL UNIQUE,
  tipo ENUM('Pequeña', 'Grande') NOT NULL
);

CREATE TABLE asignaciones_de_salas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_sala INT NOT NULL,
  id_aula INT NOT NULL,
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_sala) REFERENCES salas (id),
  FOREIGN KEY (id_aula) REFERENCES aulas (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

/*
CHULETA: Extraer los docentes de una inscripción

select
concat(estudiantes.nombres, ' ', estudiantes.apellidos) as estudiante,
inscripciones.id_asignacion_docente,
inscripciones.id_asignacion_asistente,
inscripciones.id_asignacion_segundo_asistente
from inscripciones
join estudiantes
join asignaciones_de_docentes
on inscripciones.id_estudiante = estudiantes.id
and (inscripciones.id_asignacion_docente = asignaciones_de_docentes.id_docente)
;
 */
