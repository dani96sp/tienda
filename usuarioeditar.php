<?php
$titulo_pagina = "Editar Datos Personales";
$descripcion = "editar datos personales";
$keywords = "editar, datos, personales, palabras clave, keywords";

//Inluimos las funciones
include("funciones.php");

require("seguridad.php");

$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
//acentos
$con->query("SET NAMES 'utf8'");

$username = $_SESSION['login_user'];
$sql = "SELECT * FROM usuarios WHERE username = '$username'";

	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$nombre = $row["nombre"];
			$telefono = $row["telefono"];
			$email = $row["email"];
		}
		mysqli_free_result($result);
	}

if(isset($_REQUEST['enviar'])){
	$nombre = $_POST["nombre"];
	$telefono = $_POST["telefono"];
	$email = $_POST["email"];

	//validar campos
	$er_nombre = "/^([A-ZÁÉÍÓÚ]{1}[a-zñáéíóú]+[\s]*){2,}$/";
	$er_telefono = "/^\d{9}$/";
	$er_email = "/^(.+\@.+\..+)$/"; 

	if (!preg_match($er_nombre, $nombre)) {
		$nombre = "";
	}

	if (!preg_match($er_telefono, $telefono)) {
		$telefono = "";
	}
	if (!preg_match($er_email, $email)) {
		$email = "";
	}
	
	if(empty($nombre)|empty($telefono)|empty($email)) {
		//los que esten vacios se ponen de rojo
		if(empty($nombre)) {
			$cNombre = "incorrecto";
		} else {
			$cNombre = "correcto";
		}

		if(empty($telefono)) {
			$cTelefono = "incorrecto";
		} else {
			$cTelefono = "correcto";
		}

		if(empty($email)) {
			$cEmail = "incorrecto";
		} else {
			$cEmail = "correcto";
		}
		
		echo "<h1>Algunos datos son incorrectos</h1>";

	} else { // si todo es correcto modificamos y le indicamos al usuario que todo ha ido correctamente
		$sql = "UPDATE usuarios SET nombre = '$nombre', telefono = '$telefono', email = '$email' WHERE username = '$username'";
		mysqli_query($con, $sql);
		echo "<h1><i>Cambios guardados</i></h1>";
	}
}
mysqli_close($con);

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>


<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>

<fieldset>
<legend>Modificar datos</legend>
<form name="formulario" id="formulario" action="usuarioeditar.php" method="post">
  <label for="nombre" id="<?php echo $cNombre ?>">Nombre y apellidos:</label>
  <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $nombre ?>"/><br/>
  <label for="telefono" id="<?php echo $cTelefono ?>">Teléfono:</label>
  <input name="telefono" type="text" id="telefono" maxlength="9" value="<?php echo $telefono ?>"/><br/>
  <label for="email" id="<?php echo $cEmail ?>">E-mail:</label>
  <input name="email" type="text" id="email" maxlength="40" value="<?php echo $email ?>" /><br/>
  <input type="submit" name="enviar" id="enviar" value="Guardar datos"/>
  <input type="reset" name="limpiar" id="button" value="Restablecer datos" ><br/>
</form> 
</fieldset>

<?php
echo "<br/><br/>";
include("pie.php");
//Por último incluimos el pie
?>
