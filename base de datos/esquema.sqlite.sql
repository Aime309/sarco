PRAGMA foreign_keys = OFF;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS representantes;
DROP TABLE IF EXISTS estudiantes;
DROP TABLE IF EXISTS inscripciones;
DROP TABLE IF EXISTS asignaciones_de_docentes;
DROP TABLE IF EXISTS salas;
DROP TABLE IF EXISTS periodos;
DROP TABLE IF EXISTS boletines;
DROP TABLE IF EXISTS momentos;
DROP TABLE IF EXISTS asignaciones_de_salas;
DROP TABLE IF EXISTS aulas;

CREATE TABLE usuarios (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula INTEGER NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1906-01-01'),
  genero VARCHAR(9) NOT NULL CHECK (genero IN ('Femenino', 'Masculino')),
  telefono CHAR(15) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 15 AND telefono LIKE '+__ ___-_______'),
  correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE '%@%.%'),
  direccion TEXT NOT NULL CHECK (LENGTH(direccion) >= 3),
  clave TEXT NOT NULL CHECK (LENGTH(clave) >= 8),
  esta_activo BOOL DEFAULT TRUE,
  rol VARCHAR(12) NOT NULL CHECK (rol IN ('Director', 'Docente', 'Secretario')),

  UNIQUE (nombres, apellidos)
);

CREATE TABLE representantes (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula INTEGER NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1906-01-01'),
  genero VARCHAR(9) NOT NULL CHECK (genero IN ('Femenino', 'Masculino')),
  telefono CHAR(15) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 15 AND telefono LIKE '+__ ___-_______'),
  correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE '%@%.%'),
  estado_civil VARCHAR(12) NOT NULL CHECK (estado_civil IN ('Casado', 'Soltero', 'Divorciado', 'Viudo')),
  nacionalidad CHAR(10) NOT NULL CHECK (nacionalidad IN ('Venezolano', 'Extranjero')),

  UNIQUE (nombres, apellidos)
);

CREATE TABLE estudiantes (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula CHAR(13) NOT NULL UNIQUE CHECK (LENGTH(cedula) = 13 AND cedula LIKE 'v-1__%'),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1996-01-01'),
  genero VARCHAR(9) NOT NULL CHECK (genero IN ('Femenino', 'Masculino')),
  lugar_nacimiento TEXT NOT NULL CHECK (LENGTH(lugar_nacimiento) >= 3),
  tipo_sangre CHAR(3) NOT NULL CHECK (tipo_sangre IN ('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-')),
  id_mama VARCHAR(255) NOT NULL,
  id_papa VARCHAR(255),

  FOREIGN KEY (id_mama) REFERENCES representantes (id),
  FOREIGN KEY (id_papa) REFERENCES representantes (id)
);

CREATE TABLE salas (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  nombre VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(nombre) BETWEEN 3 AND 20),
  edad_minima INTEGER NOT NULL CHECK (edad_minima >= 0 AND edad_minima <= edad_maxima),
  edad_maxima INTEGER NOT NULL CHECK (edad_maxima <= 10 AND edad_maxima >= edad_minima),
  esta_activa BOOL NOT NULL DEFAULT TRUE
);

CREATE TABLE periodos (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  anio_inicio INTEGER NOT NULL UNIQUE CHECK (anio_inicio >= 2006)
);

CREATE TABLE momentos (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01'),
  numero INTEGER NOT NULL CHECK (numero BETWEEN 1 AND 3),
  mes_inicio INTEGER NOT NULL CHECK (mes_inicio BETWEEN 1 AND 12),
  dia_inicio INTEGER NOT NULL CHECK (
    ((mes_inicio IN (1, 3, 5, 7, 8, 10, 12)) AND (dia_inicio BETWEEN 1 AND 31))
    OR ((mes_inicio IN (4, 6, 9, 11)) AND (dia_inicio BETWEEN 1 AND 30))
    OR ((mes_inicio = 2) AND (dia_inicio BETWEEN 1 AND 29))
  ),
  mes_cierre INTEGER NOT NULL CHECK (mes_cierre BETWEEN 1 AND 12),
  dia_cierre INTEGER NOT NULL CHECK (
    ((mes_cierre IN (1, 3, 5, 7, 8, 10, 12)) AND (dia_cierre BETWEEN 1 AND 31))
    OR ((mes_cierre IN (4, 6, 9, 11)) AND (dia_cierre BETWEEN 1 AND 30))
    OR ((mes_cierre = 2) AND (dia_cierre BETWEEN 1 AND 29))
  ),
  id_periodo VARCHAR(255) NOT NULL,

  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE aulas (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01'),
  codigo VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(codigo) BETWEEN 1 AND 20),
  tipo VARCHAR(7) NOT NULL CHECK (tipo IN ('Pequeña', 'Grande'))
);

CREATE TABLE asignaciones_de_salas (
  id VARCHAR(255) PRIMARY KEY,
  id_sala VARCHAR(255) NOT NULL,
  id_aula VARCHAR(255) NOT NULL,
  id_periodo VARCHAR(255) NOT NULL,
  id_docente1 VARCHAR(255) NOT NULL,
  id_docente2 VARCHAR(255) NOT NULL,
  id_docente3 VARCHAR(255),

  FOREIGN KEY (id_sala) REFERENCES salas (id),
  FOREIGN KEY (id_aula) REFERENCES aulas (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id),
  FOREIGN KEY (id_docente1) REFERENCES usuarios (id),
  FOREIGN KEY (id_docente2) REFERENCES usuarios (id),
  FOREIGN KEY (id_docente3) REFERENCES usuarios (id)
);

CREATE TABLE inscripciones (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  id_periodo VARCHAR(255) NOT NULL,
  id_estudiante VARCHAR(255) NOT NULL,
  id_asignacion_sala VARCHAR(255) NOT NULL,

  FOREIGN KEY (id_periodo) REFERENCES periodos (id),
  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_asignacion_sala) REFERENCES asignaciones_de_salas (id)
);

CREATE TABLE boletines (
  id VARCHAR(255) PRIMARY KEY,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  numero_inasistencias INTEGER NOT NULL CHECK (numero_inasistencias BETWEEN 0 AND 121),
  nombre_proyecto VARCHAR(255) NOT NULL CHECK (LENGTH(nombre_proyecto) >= 3),
  descripcion_formacion TEXT NOT NULL CHECK (LENGTH(descripcion_formacion) >= 3),
  descripcion_ambiente TEXT NOT NULL CHECK (LENGTH(descripcion_ambiente) >= 3),
  recomendaciones TEXT NOT NULL CHECK (LENGTH(recomendaciones) >= 3),
  id_estudiante VARCHAR(255) NOT NULL,
  id_momento VARCHAR(255) NOT NULL,
  id_asignacion_sala VARCHAR(255) NOT NULL,

  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_momento) REFERENCES momentos (id),
  FOREIGN KEY (id_asignacion_sala) REFERENCES asignaciones_de_salas (id)
);

INSERT INTO salas (id, nombre, edad_minima, edad_maxima) VALUES
('c8bd41c9-2484-407d-bc5c-87011cb94640', 'Maternal', 0, 2),
('008e6ed-5902-4621-b5fa-98d605565a24', 'De 3 Única', 3, 3),
('636ebd99-8414-4c7b-b1e6-422781bba4cf', 'Mixta 3-4', 3, 4),
('f3366f8d-b7ea-4527-b983-2aa875b63c95', 'De 4 Única', 4, 4),
('8d3e31f7-7a4a-4988-a4ac-8d4b4f71de5e', 'Mixta 4-5', 4, 5),
('e4f3059b-1d72-4140-a87f-2ea3785995fe', 'De 5 Única', 5, 5);

INSERT INTO aulas (id, codigo, tipo) VALUES
('e01757b7-6f31-4001-83b3-f2a9db4c6033', 'AULA-G1', 'Grande'),
('578d5ac0-1356-419f-bae1-ca87183642b6', 'AULA-G2', 'Grande'),
('cd4fde47-fc4f-4dd9-a447-08a5bd8cadaf', 'AULA-G3', 'Grande'),
('17eccf38-25f0-45c9-86e8-9ee2957b7e12', 'AULA-G4', 'Grande'),
('6d48cf77-c382-42ef-9dbf-cecba321b539', 'AULA-P1', 'Pequeña'),
('99f3eff7-64db-4ba5-a9d9-71af37ac4731', 'AULA-P2', 'Pequeña');

PRAGMA foreign_keys = ON;
