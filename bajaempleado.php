<?php
$titulo_pagina = "Baja Empleado";
$descripcion = "Baja empleado";
$keywords = "baja, empleado, palabras clave, keywords";

//Inluimos las funciones
include("funciones.php");
include("seguridad.php");
//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");


$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
//acentos
$con->query("SET NAMES 'utf8'");

if(isset($_POST['username'])) {
	$username = $_POST["username"];
}

$sql = "UPDATE usuarios SET tipo = 'Cliente' WHERE username = '$username'";
if ($result = mysqli_query($con, $sql)) {
	$resultado = 'Se ha dado de baja al empleado '.$username.' correctamente';
} else {
	$resultado = 'Ha habido un problema al dar de baja al empleado '.$username;
}

mysqli_close($con);
?>


<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>

<?php
echo $resultado;
echo "<br/><br/>";
include("pie.php");
?>
