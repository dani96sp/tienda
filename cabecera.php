<html>
<head>
	<title><?php parametro_plantilla("titulo_pagina"); ?></title>
	<link rel="STYLESHEET" type="text/css" href="estilo.css">
	<meta name="Description" content='<?php parametro_plantilla("descripcion"); ?>'>
	<meta name="Keywords" content='<?php parametro_plantilla("keywords"); ?>'>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>

<body>
<!-- Inicio Borde -->
<div id="borde">
    <!-- Inicio Contenedor -->
    <div id="contenedor">
	<!-- Inicio Cabecera -->
	<div id="cabecera"><img id="imgcabecera" src="img/cabecera.png" width="700" height="106" alt="Tienda Daniel Muñoz" border="0">
	</div>
	<!-- Fin Cabecera -->

	<!-- Inicio Navegador -->
	<div id="navegador">
		<a href="index.php" class="enlacenav">Inicio</a> |  
		<a href="ofertas.php" class="enlacenav">Ofertas</a> | 
		<a href="#" class="enlacenav">Contacto</a> | 
		<a href="#" class="enlacenav">Información sobre los envíos</a> | 
		<a href="#" class="enlacenav">Condiciones generales</a>
				<!-- Inicio Buscar Artículos -->
		<div id="fbuscar">
			<form id="buscador" name="buscador" method="GET" action="buscararticulos.php">
				<div id="campotexto"><input type="search" name="buscar" id="buscar" placeholder="Buscar artículos"></div>
				<div id="botonbuscar">
					<input type="submit" value="Go">
				</div>
			</form>
		</div>
		<!-- Fin Buscar Artículos -->
	</div>
	<!-- Fin Navegador -->

	<!--Inicio Lateral Derecho -->
	<div id="lateral">
		<!-- Inicio Login -->
		<div id="login">
<?php
	// Establishing Connection with Server by passing server_name, user_id and password as a parameter
	$connection = @mysql_connect(HOSTNAME, USER_DB, PASSWORD_DB);
	//arreglar problema con acentos
	mysql_query("SET NAMES 'utf8'");
	$error=''; // Variable To Store Error Message

	//Si se ha enviado un formulario se comprueban los datos de login
	if (isset($_POST['submit'])) {
		// Define $username and $password
		$username=$_POST['username'];
		$password=$_POST['password'];
		$username = strtolower($username);
		// To protect MySQL injection for Security purpose
		$username = stripslashes($username);
		$password = stripslashes($password);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		// Selecting Database
		$db = mysql_select_db(DATABASE, $connection);
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("select password from usuarios WHERE username='$username'", $connection);
		$passdb = mysql_result($query, 0);
		if (password_verify($password, $passdb)) {
			$_SESSION['login_user']=$username; // Initializing Session
		} else {
			$error = "Usuario o contraseña incorrecto";
		}
	}
	//Si hay una sesión (usuario logueado) incluye profile.php
	if(isset($_SESSION['login_user'])){
		include ('profile.php');
	}
	else {
		//Si no hay una sesión (usuario no logueado) incluye el formulario de login
		include ('login.php');
	}
?>
		<!-- Inicio Carrito de la Compra -->
		<h2 class="titlat">Carrito de la compra</h2>
		<div id="otras" class="cuerpolateral">
<?php 
// Se muestra el carrito
include ("vercarrito.php");
?>
		</div>
		<!-- Fin Carrito de la Compra -->

	</div>
	<!-- Fin Lateral Derecho -->

	<!-- Inicio Lateral Izquierdo -->
	<div id="lateral2">
		<!-- Inicio Categorías -->
		<h2 class="titlat">Categorías</h2>
		<div id="otras" class="cuerpolateral">
		<ul>

<?php
//Llamada a la función mostrarCategorías()
mostrarCategorias();

?>
		</ul>
		</div>
		<!-- Fin Categorías -->
	</div>
	<!-- Fin Lateral Izquierdo -->

	<!-- Inicio Cuerpo -->
	<div id="cuerpo">
