<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Menu</title>
<style type="text/css">

</head>
<body>
    <header>
	



/* Add responsive styles here */
@media screen and (max-width: 768px) {
  .nav > li {
    display: block;
    width: 100%;
  }
  .nav li ul {
    position: relative;
    width: 100%;
	
  }
  .nav li ul li {
    padding: 5px;
  }
  .nav li ul li ul {
    left: 0;
    top: 0;
    right: auto;
  }
  #header {
    position: relative;
    width: 100%;
  }
   
    
  
}

/* Original styles */
.Estilo1 {
	font-size: 26px;
	font-family: "Lucida Calligraphy";
	font-weight: bold;
	color: #0060BF;

}
.Estilo2{
	font-family: "Bookman Old Style"
}
.Estilo3 {
	font-family: "Lucida Calligraphy";
	font-size: 16px;
	color: #0060BF;
	font-weight: bold;
}
.Estilo4 {
	color: #000000;
	font-size: 12px;
}
.Estilo5 {
	font-family: "Bookman Old Style";
	font-size: 18px;
	font-weight: bold;
}
.Estilo8 {	font-size: 16px;
	font-weight: bold;
}
.Estilo9 {font-family: "Bookman Old Style"; font-size: 10px; font-weight: bold; color: #FF0000; }
a:link {
	color: #0060BF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #0060BF;
}
a:hover {
	text-decoration: underline;
}
a:active {
	text-decoration: none;
}




#header {
	background: #16A085;
	width:100%;
	font-family:Style Bookman Old ;
	font-weight:bold;
	position: fixed;
	top: 0;
	left: 0;
	z-index:0;
}


ul, ol {
	list-style:none;
}

.nav > li {
	float:left;
}

.nav li a {
	background-color: #45B39D;
	color:#000000;
	text-decoration:none;
	padding:9px 8px;
	display:block;
	border:0px solid #0060BF;
	margin:1px;

}

.nav li .flecha{
	font-size: 7px;
	padding-left: 4px;
	display: none;
}

.nav li a:not(:last-child) 

.flecha {
	display: inline;
}

.nav li a:hover {
	background: #16A085;
	border :0 px solid;
}

.nav li ul {
	display:none;
	position:absolute;
	min-width:140px;
	padding:5px 10px;
	font-size:10px;
	border-radius:5px;
	padding:10px;
}

.nav li:hover > ul {
	display:block;
}

.nav li ul li {
	position:relative;
	padding:3px 10px;
}

.nav li ul li ul {
	right:-140px;
	top:0px;
}



.my-button {
	background-color: #4CAF50; /* Green */
	border: none;
	color: white;
	padding: 15px 14px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	margin: 4px 2px;
	cursor: pointer;
}
</style>
</head>

<body>
<table width="1200px" border="0" align="left">
     <tr class="Estilo3">
      <ul class="nav">
          <li><a href="administrador.php">Inicio</a></li>
		  

         
          </li>
          <li><a href="">Estudiantes</a>
              <ul>

                <li><a href="./pagina/consultar_es.php">consultar</a></li>
				<li><a href=" ./pagina/registrar_es.php">registrar</a></li>
				 <li><a href="./pagina/modificar_es.php">modificar</a></li>
				 <li><a href="./pagina/eliminar_es.php">Eliminar</a></li>
				 <li><a href="./pagina/reportes-es.php">Reportes</a></li>
				 </ul>

				 </li>
          <li><a href="">Representantes</a>
              <ul>

                <li><a href="./pagina/consultar_res.php">consultar</a></li>
				<li><a href=" ./pagina/registrar_res.php">registrar</a></li>
				 <li><a href="./pagina/modificar_res.php">modificar</a></li>
				 <li><a href="./pagina/eliminar_res.php">Eliminar</a></li>
				 <li><a href="./pagina/reportes-res.php">Reportes</a></li>
				 </ul>

                </li>
               <li><a href="">Salas</a>
			   <ul>
                      <li><a href="./pagina/registrar_sal.php">Registrar</a></li>
                      <li><a href="./pagina/consultar_sal.php">Consultar</a></li>
					  <li><a href="./pagina/modificar_sal.php">Modificar</a></li>
					  <li><a href="./pagina/eliminar_sal.php">Eliminar</a></li>
					  <li><a href="./pagina/reportes_sal.php">Reportes</a></li>
                    </ul>

					</li>
					 <li><a href="">Momentos</a>
					 <ul>

                <li><a href="./pagina/reportes_momen.php">Reportes</a></li>
				<li><a href="./pagina/registrar_momen.php">Registrar</a></li>
				<li><a href="./pagina/consultar_momen.php">Consultar</a></li>
				<li><a href="./pagina/modificar_momen.php">Modificar</a></li>
				<li><a href="./pagina/eliminar_momen.php">Eliminar</a></li>
                </ul>
            
            
          </li>
          <li><a href="">Maestros</a>
		  <ul>

			         <li><a href="./pagina/registrar_maes.php">Registrar</a></li>
                      <li><a href="./pagina/consultar_maes.php">Consultar</a></li>
					  <li><a href="./pagina/modificar_maes.php">Modificar</a></li>
					  <li><a href="./pagina/eliminar_maes.php">Eliminar</a></li>
					  <li><a href="./pagina/reportes_maes.php">Reportes</a></li>
					  </ul>

          </li>
          <li><a href="">Observasiones</a>
		  <ul>

                <li><a href="./pagina/reportes_ob.php">Reportes</a></li>
				<li><a href="./pagina/registrar_ob.php">Registrar</a></li>
				<li><a href="./pagina/consultar_ob.php">Consultar</a></li>
				<li><a href="./pagina/reportes_ob.php">Modificar</a></li>
				<li><a href="./pagina/eliminar_ob.php">Eliminar</a></li>
				</ul>

          </li>
          <li><a href="">periodos</a>
		  <ul>

                <li><a href="./pagina/reportes_perio.php">Reportes</a></li>
				<li><a href="./pagina/registrar_perio.php">Registrar</a></li>
				<li><a href="./pagina/consultar-perio.php">Consultar</a></li>
				<li><a href="./pagina/reportes_perio.php">Modificar</a></li>
				<li><a href="./pagina/eliminar_perio.php">Eliminar</a></li>
				</ul>

          </li>
          <li><a href="">Respaldo</a>
		  <ul>

			  <li><a href="./Pagina/registrar_res.php">Registrar</a></li>
			  <li><a href="./Pagina/consultar_res.php">consultar</a></li>
			  <li><a href="./Pagina/modificar_res.php">Modificar</a></li>
			  <li><a href="./Pagina/eliminar_res.php">Eliminar</a></li>
			  <li><a href="./Pagina/respaldo_res.php">Reportes</a></li>
              </ul>

          </li>
          <li><a href="">Reportes</a>
		  <ul>

                <li><a href="./pagina/registrar_repo.php">Registrar</a></li>
                <li><a href="./pagina/consultar_repo.php">Consultar</a></li>
                <li><a href="./pagina/modificar_repo.php">Modificar</a></li>
             <li><a href="./pagina/eliminar_repo.php">Eliminar</a></li>
            	<li><a href="./pagina/reportes_repo.php">Resportes</a></li>
				</ul>

          </li>
          <li><a href="">Seguridad</a>
		  <ul>

              <li><a href="../Seguridad/BBDD/exportar.php">Respaldar</a></li>
                      <li><a href="../Seguridad/BBDD/importar.php">Restaurar</a></li>
                      <li><a href="../Seguridad/BBDD/backupBBDD.php">Extraer</a></li>
					   <li><a href="registro_usuario.html">Registrar</a></li>
                      <li><a href="listado_usuarios.php">Listado</a></li>
				   <ul>
					   
                   </li>
				   
				</tr>
	
</table>
</body>
</html>
