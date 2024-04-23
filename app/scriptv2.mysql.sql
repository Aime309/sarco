SET foreign_key_checks = 0;
SET time_zone = '-04:30';

DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS representantes;
DROP TABLE IF EXISTS estudiantes;
DROP TABLE IF EXISTS inscripciones;
DROP TABLE IF EXISTS asignaciones_de_docentes;
DROP TABLE IF EXISTS docentes;
DROP TABLE IF EXISTS salas;
DROP TABLE IF EXISTS periodos;
DROP TABLE IF EXISTS boletines;
DROP TABLE IF EXISTS momentos;
DROP TABLE IF EXISTS asignaciones_de_salas;
DROP TABLE IF EXISTS aulas;

CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula INT NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1906-01-01'),
  genero ENUM('Femenino', 'Masculino') NOT NULL,
  direccion TEXT NOT NULL CHECK (LENGTH(direccion) >= 3),
  telefono CHAR(16) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 16 AND telefono LIKE '+__ ___ ___ ____' /* AND telefono REGEXP '^\+\d{2} \d{3} \d{3} \d{4}$' */),
  correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE '%@%.%'),
  usuario VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(usuario) BETWEEN 3 AND 20),
  clave TEXT NOT NULL CHECK (LENGTH(clave) >= 8),
  rol ENUM('Director/a', 'Docente', 'Secretario/a') NOT NULL,
  esta_activo BOOL DEFAULT TRUE,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),

  UNIQUE (nombres, apellidos)
);

CREATE TABLE representantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula INT NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1906-01-01'),
  genero ENUM('Femenino', 'Masculino') NOT NULL,
  estado_civil ENUM('Casado/a', 'Soltero/a', 'Divorciado/a', 'Viudo/a') NOT NULL,
  nacionalidad ENUM('Venezolano/a', 'Extranjero/a') NOT NULL,
  telefono CHAR(16) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 16 AND telefono LIKE '+__ ___ ___ ____' /* AND telefono REGEXP '^\+\d{2} \d{3} \d{3} \d{4}$' */),
  correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE '%@%.%'),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),

  UNIQUE (nombres, apellidos)
);

CREATE TABLE estudiantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula_escolar CHAR(13) NOT NULL UNIQUE CHECK (LENGTH(cedula_escolar) = 13 AND cedula_escolar LIKE 'v-1__%' /* AND cedula_escolar REGEXP '^v-1(\d{2}|0\d{1})\d{1,}$' */),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1996-01-01'),
  lugar_nacimiento TEXT NOT NULL CHECK (LENGTH(lugar_nacimiento) >= 3),
  genero ENUM('Femenino', 'Masculino') NOT NULL,
  tipo_sangre ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  id_mama INT NOT NULL,
  id_papa INT,

  FOREIGN KEY (id_mama) REFERENCES representantes (id),
  FOREIGN KEY (id_papa) REFERENCES representantes (id)
);

CREATE TABLE salas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(nombre) BETWEEN 3 AND 20),
  edad_minima INT NOT NULL CHECK (edad_minima > 0 AND edad_minima < edad_maxima),
  edad_maxima INT NOT NULL CHECK (edad_maxima <= 10 AND edad_maxima > edad_minima),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00')
);

CREATE TABLE periodos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  anio_inicio INT NOT NULL UNIQUE CHECK (anio_inicio >= 2006),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00')
);

CREATE TABLE asignaciones_de_docentes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_docente INT NOT NULL,
  id_sala INT NOT NULL,
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_docente) REFERENCES usuarios (id),
  FOREIGN KEY (id_sala) REFERENCES salas (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE inscripciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
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
  numero_momento ENUM('1', '2', '3') NOT NULL,
  mes_inicio INT NOT NULL CHECK (mes_inicio BETWEEN 1 AND 12),
  -- dia_inicio INT NOT NULL CHECK (CASE
  --   WHEN mes_inicio IN (1, 3, 5, 7, 8, 10, 12) THEN dia_inicio BETWEEN 1 AND 31
  --   WHEN mes_inicio IN (4, 6, 9, 11) THEN dia_inicio BETWEEN 1 AND 30
  --   WHEN mes_inicio = 2 THEN dia_inicio BETWEEN BETWEEN 1 AND 29
  -- END),
  dia_inicio INT NOT NULL CHECK (
    ((mes_inicio IN (1, 3, 5, 7, 8, 10, 12)) AND (dia_inicio BETWEEN 1 AND 31))
    OR ((mes_inicio IN (4, 6, 9, 11)) AND (dia_inicio BETWEEN 1 AND 30))
    OR ((mes_inicio = 2) AND (dia_inicio BETWEEN 1 AND 29))
  ),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01'),
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE boletines (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero_inasistencias INT NOT NULL CHECK (numero_inasistencias BETWEEN 0 AND 121),
  nombre_proyecto VARCHAR(255) NOT NULL CHECK (LENGTH(nombre_proyecto) >= 3),
  descripcion_formacion TEXT NOT NULL CHECK (LENGTH(descripcion_formacion) >= 3),
  descripcion_ambiente TEXT NOT NULL CHECK (LENGTH(descripcion_ambiente) >= 3),
  recomendaciones TEXT NOT NULL CHECK (LENGTH(recomendaciones) >= 3),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  id_estudiante INT NOT NULL,
  id_momento INT NOT NULL,
  id_docente INT NOT NULL,
  id_asistente INT NOT NULL,
  id_segundo_asistente INT,

  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_momento) REFERENCES momentos (id),
  FOREIGN KEY (id_docente) REFERENCES usuarios (id),
  FOREIGN KEY (id_asistente) REFERENCES usuarios (id),
  FOREIGN KEY (id_segundo_asistente) REFERENCES usuarios (id)
);

CREATE TABLE aulas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  codigo_sala VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(codigo_sala) BETWEEN 1 AND 20),
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

 INSERT INTO usuarios (nombres,apellidos,cedula,usuario,clave,rol,fecha_nacimiento,direccion,telefono,correo,fecha_registro,esta_activo,genero)
 VALUES
   ("TaShya Wheeler","Damon Cabrera",23371011,"EOPN","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Director/a","1993-06-30","6324 In, St.","+23 842 277 7185","eros.nec@yahoo.ca","2024-04-07 13:34:55",false, 'Femenino'),
   ("Odette Thornton","Brian Hoffman",22244934,"MYCR","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Director/a","1996-10-27","Ap #385-8616 A, St.","+88 142 982 1625","proin.nisl@protonmail.ca","2024-03-25 17:35:51",false, 'Masculino'),
   ("Kylie Kelley","Noble Hobbs",21108739,"XJLR","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Secretario/a","1997-06-18","Ap #885-9597 Integer Rd.","+37 155 865 3327","urna.convallis.erat@aol.ca","2024-04-15 07:01:52",true, 'Femenino'),
   ("Dennis Rocha","Ursa Porter",23790083,"BWWY","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Docente","1996-09-28","6083 Vel Ave","+55 284 442 8144","nunc@yahoo.org","2024-04-06 22:55:36",true, 'Femenino'),
   ("Suki Frazier","Kerry Pittman",21531366,"FMQL","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Secretario/a","1999-07-13","Ap #577-6943 Mauris Rd.","+56 193 291 8040","sit@protonmail.ca","2024-01-22 15:55:58",true, 'Femenino'),
   ("Gary Goodwin","Kalia Lowe",24500477,"WQRH","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Docente","1999-01-14","Ap #746-4633 Et Rd.","+77 662 416 3284","ornare@aol.ca","2024-02-02 01:13:34",true, 'Masculino'),
   ("Paloma Fitzpatrick","Harper Simpson",23266913,"FSJA","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Secretario/a","1991-06-08","537-1030 Donec Street","+58 772 307 9515","euismod@icloud.edu","2024-01-04 19:02:10",false, 'Masculino'),
   ("Patrick Edwards","Glenna Ryan",20207733,"ZBYO","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Secretario/a","1994-04-27","Ap #979-602 Vitae, Rd.","+20 719 253 6134","arcu@google.org","2024-02-24 15:03:25",false, 'Masculino'),
   ("Herrod Cruz","Amanda Martinez",22737463,"ESYQ","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Docente","1995-04-20","7511 Dolor Ave","+85 816 373 1923","euismod@google.org","2024-03-12 05:19:14",false, 'Masculino'),
   ("Sierra Reed","Holmes Hewitt",22819616,"SNUL","$2y$10$bXIDLzJuwR8xVTJOVm9thuk38BaY2jNxN/4kaoeBeCslncRxDzdQS","Docente","1995-03-03","Ap #503-7081 Amet, Av.","+82 127 321 2834","ipsum.donec@icloud.edu","2024-01-11 23:33:32",false, 'Femenino');
