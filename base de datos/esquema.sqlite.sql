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
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula INTEGER NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1906-01-01'),
  direccion TEXT NOT NULL CHECK (LENGTH(direccion) >= 3),
  telefono CHAR(15) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 15 AND telefono LIKE '+__ ___-_______' /* AND telefono REGEXP '^\+\d{2} \d{3}-\d{7}$' */),
  correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE '%@%.%'),
  clave TEXT NOT NULL CHECK (LENGTH(clave) >= 8),
  rol VARCHAR(12) NOT NULL CHECK (rol IN ('Director', 'Directora', 'Docente', 'Secretario', 'Secretaria')),
  esta_activo BOOL DEFAULT TRUE,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),

  UNIQUE (nombres, apellidos)
);

CREATE TABLE representantes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula INTEGER NOT NULL UNIQUE CHECK (cedula BETWEEN 1000000 AND 99999999),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1906-01-01'),
  estado_civil VARCHAR(12) NOT NULL CHECK (estado_civil IN ('Casado', 'Casada', 'Soltero', 'Soltera', 'Divorciado', 'Divorciada', 'Viudo', 'Viuda')),
  nacionalidad CHAR(10) NOT NULL CHECK (nacionalidad IN ('Venezolano', 'Venezolana', 'Extranjero', 'Extrajera')),
  telefono CHAR(15) NOT NULL UNIQUE CHECK (LENGTH(telefono) = 15 AND telefono LIKE '+__ ___-_______' /* AND telefono REGEXP '^\+\d{2} \d{3}-\d{7}$' */),
  correo VARCHAR(255) NOT NULL UNIQUE CHECK (LENGTH(correo) >= 5 AND correo LIKE '%@%.%'),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),

  UNIQUE (nombres, apellidos)
);

CREATE TABLE estudiantes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombres VARCHAR(40) NOT NULL CHECK (LENGTH(nombres) BETWEEN 3 AND 40),
  apellidos VARCHAR(40) NOT NULL CHECK (LENGTH(apellidos) BETWEEN 3 AND 40),
  cedula_escolar CHAR(13) NOT NULL UNIQUE CHECK (LENGTH(cedula_escolar) = 13 AND cedula_escolar LIKE 'v-1__%' /* AND cedula_escolar REGEXP '^v-1(\d{2}|0\d{1})\d{1,}$' */),
  fecha_nacimiento DATE NOT NULL CHECK (fecha_nacimiento >= '1996-01-01'),
  lugar_nacimiento TEXT NOT NULL CHECK (LENGTH(lugar_nacimiento) >= 3),
  genero VARCHAR(9) NOT NULL CHECK (genero IN ('Femenino', 'Masculino')),
  tipo_sangre CHAR(3) NOT NULL CHECK (tipo_sangre IN ('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-')),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  id_mama INTEGER NOT NULL,
  id_papa INTEGER,

  FOREIGN KEY (id_mama) REFERENCES representantes (id),
  FOREIGN KEY (id_papa) REFERENCES representantes (id)
);

CREATE TABLE salas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(nombre) BETWEEN 3 AND 20),
  edad_minima INTEGER NOT NULL CHECK (edad_minima >= 0 AND edad_minima <= edad_maxima),
  edad_maxima INTEGER NOT NULL CHECK (edad_maxima <= 10 AND edad_maxima >= edad_minima),
  esta_activa BOOL NOT NULL DEFAULT TRUE,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00')
);

CREATE TABLE periodos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  anio_inicio INTEGER NOT NULL UNIQUE CHECK (anio_inicio >= 2006),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00')
);

CREATE TABLE asignaciones_de_docentes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  id_docente INTEGER NOT NULL,
  id_sala INTEGER NOT NULL,
  id_periodo INTEGER NOT NULL,

  FOREIGN KEY (id_docente) REFERENCES usuarios (id),
  FOREIGN KEY (id_sala) REFERENCES salas (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE inscripciones (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  id_momento INTEGER NOT NULL,
  id_estudiante INTEGER NOT NULL,
  id_asignacion_docente INTEGER NOT NULL,
  id_asignacion_asistente INTEGER NOT NULL,
  id_asignacion_segundo_asistente INTEGER,

  FOREIGN KEY (id_momento) REFERENCES momentos (id),
  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_asignacion_docente) REFERENCES asignaciones_de_docentes (id),
  FOREIGN KEY (id_asignacion_asistente) REFERENCES asignaciones_de_docentes (id),
  FOREIGN KEY (id_asignacion_segundo_asistente) REFERENCES asignaciones_de_docentes (id)
);

CREATE TABLE momentos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  numero_momento INTEGER NOT NULL CHECK (numero_momento BETWEEN 1 AND 3),
  mes_inicio INTEGER NOT NULL CHECK (mes_inicio BETWEEN 1 AND 12),
  -- dia_inicio INTEGER NOT NULL CHECK (CASE
  --   WHEN mes_inicio IN (1, 3, 5, 7, 8, 10, 12) THEN dia_inicio BETWEEN 1 AND 31
  --   WHEN mes_inicio IN (4, 6, 9, 11) THEN dia_inicio BETWEEN 1 AND 30
  --   WHEN mes_inicio = 2 THEN dia_inicio BETWEEN BETWEEN 1 AND 29
  -- END),
  dia_inicio INTEGER NOT NULL CHECK (
    ((mes_inicio IN (1, 3, 5, 7, 8, 10, 12)) AND (dia_inicio BETWEEN 1 AND 31))
    OR ((mes_inicio IN (4, 6, 9, 11)) AND (dia_inicio BETWEEN 1 AND 30))
    OR ((mes_inicio = 2) AND (dia_inicio BETWEEN 1 AND 29))
  ),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01'),
  id_periodo INTEGER NOT NULL,

  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE boletines (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  numero_inasistencias INTEGER NOT NULL CHECK (numero_inasistencias BETWEEN 0 AND 121),
  nombre_proyecto VARCHAR(255) NOT NULL CHECK (LENGTH(nombre_proyecto) >= 3),
  descripcion_formacion TEXT NOT NULL CHECK (LENGTH(descripcion_formacion) >= 3),
  descripcion_ambiente TEXT NOT NULL CHECK (LENGTH(descripcion_ambiente) >= 3),
  recomendaciones TEXT NOT NULL CHECK (LENGTH(recomendaciones) >= 3),
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP CHECK (fecha_registro > '2006-01-01 00:00:00'),
  id_estudiante INTEGER NOT NULL,
  id_momento INTEGER NOT NULL,
  id_docente INTEGER NOT NULL,
  id_asistente INTEGER NOT NULL,
  id_segundo_asistente INTEGER,

  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_momento) REFERENCES momentos (id),
  FOREIGN KEY (id_docente) REFERENCES usuarios (id),
  FOREIGN KEY (id_asistente) REFERENCES usuarios (id),
  FOREIGN KEY (id_segundo_asistente) REFERENCES usuarios (id)
);

CREATE TABLE aulas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  codigo_sala VARCHAR(20) NOT NULL UNIQUE CHECK (LENGTH(codigo_sala) BETWEEN 1 AND 20),
  tipo VARCHAR(7) NOT NULL CHECK (tipo IN ('Pequeña', 'Grande'))
);

CREATE TABLE asignaciones_de_salas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  id_sala INTEGER NOT NULL,
  id_aula INTEGER NOT NULL,
  id_periodo INTEGER NOT NULL,

  FOREIGN KEY (id_sala) REFERENCES salas (id),
  FOREIGN KEY (id_aula) REFERENCES aulas (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

INSERT INTO salas (nombre, edad_minima, edad_maxima)
VALUES ('Maternal', 0, 2), ('De 3 Única', 3, 3), ('Mixta 3-4', 3, 4),
('De 4 Única', 4, 4), ('Mixta 4-5', 4, 5), ('De 5 Única', 5, 5);

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
