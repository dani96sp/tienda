<?php
$titulo_pagina = "Modificar la contraseña";
$descripcion = "modificar contraseña";
$keywords = "modificar, contraseña, palabras clave, keywords";

//Incluimos las funciones
include("funciones.php");
require("seguridad.php");
//Incluimos la cabecera
include("cabecera.php");

$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
$acentos = $con->query("SET NAMES 'utf8'");

//Sacamos el usuario y lo guardamos
$username = $_SESSION['login_user'];
$sql = "SELECT * FROM usuarios WHERE username = '$username'";

//Sacamos la contraseña y la guardamos
if ($result = mysqli_query($con, $sql)) {
	while ($row = mysqli_fetch_assoc($result)) {
		$password = $row["password"];
	}
	mysqli_free_result($result);
}

//Si se ha enviado el formulario comprobamos los datos
if(isset($_REQUEST['enviar'])){
	$npassword = $_POST["npassword"];
	$vpassword1 = $_POST["vpassword1"];
	$vpassword2 = $_POST["vpassword2"];
	//Si las 2 contraseñas actuales coinciden procedemos a comprobarla
	if ($vpassword1 == $vpassword2) {
		$password = $vpassword1;
		$connection = @mysql_connect(HOSTNAME, USER_DB, PASSWORD_DB);
		mysql_query("SET NAMES 'utf8'");
		// To protect MySQL injection for Security purpose
		$password = stripslashes($password);
		$password = mysql_real_escape_string($password);
		// Selecting Database
		$db = mysql_select_db(DATABASE, $connection);
		// SQL query to fetch information of registerd users and finds user match.

		$query = mysql_query("select password from usuarios WHERE username='$username'", $connection);
		$passdb = mysql_result($query, 0);
		if (password_verify($password, $passdb)) {
			//Una vez comprobada que la contraseña actual es correcta
			//pasamos a comprobar que la nueva cumpla los requisitos

			//validar campos
			$er_password = "/^[a-zA-Z0-9]{8,30}$/";
			
			if (!preg_match($er_password, $npassword)) {
				$npassword = "";
			}
			if(empty($npassword)) {
				if(empty($vpassword1)) {
					$cVpassword1 = "incorrecto";
				} else {
					$cVpassword1 = "correcto";
				}
				echo "<h1>Algunos datos son incorrectos</h1>";
			} else {
				// si todo es correcto modificamos y le indicamos al usuario que todo ha ido correctamente
				$pass_enc = password_hash($npassword, PASSWORD_DEFAULT);
				$sql = "UPDATE usuarios SET password = '$pass_enc' WHERE username = '$username'";
				mysqli_query($con, $sql);
				echo "<h1><i>Cambios guardados</i></h1>";
			}
		} else {
			echo "<h1>Contraseña actual incorrecta</h1>";
		}
	} // si no coinciden las contraseñas..
 	else {
		echo "<h1>Contraseña actual incorrecta</h1>";
	}
}
?>


<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>

<fieldset>
<legend>Modificar datos</legend>
<form name="formulario" id="formulario" action="cambiarpassword.php" method="post">
  <label for="npassword" id="<?php echo $cNpassword ?>">Nueva contraseña:</label>
  <input type="password" name="npassword" id="npassword" maxlength="30"/><br/>
  <label for="vpassword1" id="<?php echo $cTelefono ?>">Contraseña actual:</label>
  <input name="vpassword1" type="password" id="vpassword1" maxlength="30"/><br/>
  <label for="vpassword2" id="<?php echo $cVpassword2 ?>">Contraseña actual de nuevo:</label>
  <input name="vpassword2" type="password" id="vpassword2" maxlength="30"/><br/>
  <input type="submit" name="enviar" id="enviar" value="Guardar datos"/>
  <input type="reset" name="limpiar" id="button" value="Restablecer datos" ><br/>
</form> 
</fieldset>

<?php

echo "<br/><br/>";

//Por último incluimos el pie
include("pie.php");
?>
