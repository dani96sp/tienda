<?php 
$titulo_pagina = "Editar Pedido";
$descripcion = "editar pedido";
$keywords = "editar, pedido, palabras clave, keywords";

//Incluimos las funciones
include("funciones.php");
include("seguridad.php");
//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
//acentos
$con->query("SET NAMES 'utf8'");

//Guardamos el usuario
$username = $_SESSION['login_user'];
if(!isset($_REQUEST['enviar'])){
	if (!isset($_GET['pedido'])) {
		header("Location: index.php");
	}
	else $pedido = $_GET['pedido'];
} else {
	$pedido = $_REQUEST['pedido'];
	$estado = $_POST['estado'];
	$sql = "UPDATE pedidos SET estado = '$estado' WHERE numpedido = '$pedido'";
	mysqli_query($con, $sql);
	echo "<h1><i>Cambios guardados</i></h1>";
}

$sql = "SELECT * FROM pedidos WHERE numpedido = '$pedido'";
if ($result = mysqli_query($con, $sql)) {
	while ($row = mysqli_fetch_assoc($result)) {
		$cliente = $row["cliente"];
		$fecha = $row["fecha"];
		$estado = $row["estado"];
	}
	mysqli_free_result($result);
}

?>


<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>

<fieldset>
<legend>Datos del pedido</legend>
<form name="formulario" id="formulario" action="editarpedido.php" method="post">
  <input name="pedido" type=hidden id="pedido" value="<?php echo $pedido ?>"/>
<?php 
detallesPedido($pedido);

?>
  <label for="estado"><h1>Estado nuevo:</label>
  <select name="estado">
  	<option value="En espera">En espera</option>
  	<option value="Confirmado">Confirmado</option>
  	<option value="Enviado">Enviado</option>
  	<option value="Recibido">Recibido</option>
  </select>
  </h1>
  <input type="submit" name="enviar" id="enviar" value="Guardar datos"/>
  <input type="reset" name="limpiar" id="button" value="Restablecer datos" ><br/>
</form> 
</fieldset>

<?php
echo "<br/><br/>";

//Por último incluimos el pie
include("pie.php");
?>
