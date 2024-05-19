Algoritmo ANADIR_ESTUDIANTE_DE_2_ANOS_A_SALA
	capacidadMaternal <- 29
	Definir cantidadDeEstudiantes Como Entero
	Escribir 'Ingrese la cantidad de estudiantes de 2 años'
	Leer cantidadDeEstudiantes
	Para estudianteActual<-1 Hasta cantidadDeEstudiantes Con Paso 1 Hacer
		Si capacidadMaternal>0 Entonces
			Escribir 'Estudiante N° ', estudianteActual, ' asignado a Maternal'
			capacidadMaternal <- capacidadMaternal-1
		SiNo
			Escribir 'Sala maternal agotada'
			Escribir 'Vuelva el año siguiente, estudiante N° ', estudianteActual
			estudianteActual <- cantidadDeEstudiantes+1
		FinSi
	FinPara
FinAlgoritmo
