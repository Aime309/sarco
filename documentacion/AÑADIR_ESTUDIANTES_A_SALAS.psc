Funcion cuposMixta45 <- inscribirSalaMixta45(cuposMixta45)
	Escribir 'Intentando inscribir en Sala Mixta 4-5'
	
	Si cuposMixta45>0 Entonces
		cuposMixta45 <- cuposMixta45-1
		Escribir 'Estudiante inscrito en Sala Mixta 4-5 correctamente'
	SiNo
		Escribir 'Sala Mixta 4-5 agotada, buena suerte el año siguiente'
	FinSi
Fin Funcion

Funcion cuposMixta34 <- inscribirSalaMixta34(cuposMixta34)
	Escribir 'Intentando inscribir en Sala Mixta 3-4'
	
	Si cuposMixta34>0 Entonces
		cuposMixta34 <- cuposMixta34-1
		Escribir 'Estudiante inscrito en Sala Mixta 3-4 correctamente'
	SiNo
		Escribir 'Sala Mixta 3-4 agotada, buena suerte el año siguiente'
	FinSi
Fin Funcion

Funcion cuposMaternal <- inscribirSalaMaternal(cuposMaternal)
	Escribir 'Intentando inscribir en Sala Maternal'
	
	Si cuposMaternal>0 Entonces
		cuposMaternal <- cuposMaternal-1
		Escribir 'Estudiante inscrito en Sala Maternal correctamente'
	SiNo
		Escribir 'Sala Maternal agotada, buena suerte el año siguiente'
	FinSi
Fin Funcion

Funcion mostrarCupos(maternal, sala3, mixta34, sala4, mixta45, sala5)
	Escribir 'Quedan:'
	Escribir maternal, ' cupos en Sala Maternal'
	Escribir sala3, ' cupos en Sala de 3 Única'
	Escribir mixta34, ' cupos en Sala Mixta 3-4'
	Escribir sala4, ' cupos en Sala de 4 Única'
	Escribir mixta45, ' cupos en Sala Mixta 4-5'
	Escribir sala5, ' cupos en Sala de 5 Única'
Fin Funcion

Algoritmo asignar_estudiantes_a_salas
	Definir edadEstudiante Como Entero
	cuposMaternal <- 29
	cuposSala3 <- 29
	cuposMixta34 <- 32
	cuposSala4 <- 32
	cuposMixta45 <- 29
	cuposSala5 <- 29
	
	Mientras cuposMaternal>0 Y cuposSala3>0 Y cuposMixta34>0 Y cuposSala4>0 Y cuposMixta45>0 Y cuposSala5>0 Hacer
		mostrarCupos(cuposMaternal, cuposSala3, cuposMixta34, cuposSala4, cuposMixta45, cuposSala5)
		Escribir ''
		
		Escribir 'Ingrese edad del estudiante, (entre 0 y 5):'
		Leer edadEstudiante
		
		Escribir ''
		
		Si edadEstudiante<0 O edadEstudiante>5 Entonces
			Escribir 'Edad inválida'
		SiNo
			Si edadEstudiante<=2 Entonces
				cuposMaternal = inscribirSalaMaternal(cuposMaternal)
			SiNo
				Si edadEstudiante Es 3 Entonces
					Escribir 'Intentando inscribir en Sala de 3 Única'
					
					Si cuposSala3>0 Entonces
						cuposSala3 <- cuposSala3-1
						Escribir 'Estudiante inscrito en Sala De 3 Única correctamente'
					SiNo
						cuposMixta34 = inscribirSalaMixta34(cuposMixta34)
					FinSi
				SiNo
					Si edadEstudiante Es 4 Entonces
						Escribir 'Intentando inscribir en Sala de 4 Única'
						
						Si cuposSala4>0 Entonces
							cuposSala4 <- cuposSala4-1
							Escribir 'Estudiante inscrito en Sala De 4 Única correctamente'
						SiNo
							Escribir 'Intentando inscribir en Sala Mixta 3-4'
							
							Si cuposMixta34>0 Entonces
								cuposMixta34 <- cuposMixta34-1
								Escribir 'Estudiante inscrito en Sala Mixta 3-4 correctamente'
							SiNo
								cuposMixta45 = inscribirSalaMixta45(cuposMixta45)
							FinSi
						FinSi
					SiNo
						Si edadEstudiante Es 5 Entonces
							Escribir 'Intentando inscribir en Sala de 5 Única'
							
							Si cuposSala5>0 Entonces
								cuposSala5 <- cuposSala5-1
								Escribir 'Estudiante inscrito en Sala De 5 Única correctamente'
							SiNo
								cuposMixta45 = inscribirSalaMixta45(cuposMixta45)
							FinSi
						FinSi
					FinSi
				FinSi
			FinSi
		FinSi
		
		Escribir ''
	FinMientras
FinAlgoritmo

