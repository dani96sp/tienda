<?php
$titulo_pagina = "Compra realizada";
$descripcion = "compra realizada";
$keywords = "compra, realizada, palabras clave, keywords";

//Incluimos las funciones
require("funciones.php");

//Incluimos seguridad.php
require("seguridad.php");

//Iniciamos con la variable del número de orden en 0
$numorden = 0;

$username = $_SESSION['login_user'];
$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
$cliente = $username;
$codigo = "SELECT * FROM lineapedido WHERE numorden = '1'";
$result = mysqli_query($con, $codigo);
$rows = mysqli_num_rows($result);
$numpedido = $rows + 1;
//Procedemos a añadir el pedido
$sql = "INSERT INTO pedidos (numpedido, cliente, fecha, estado) VALUES ('$numpedido', '$cliente', CURRENT_TIMESTAMP, 'En espera')";
mysqli_query($con, $sql);
foreach ($_COOKIE["cesta_de_".$username] as $idarticulo => $unidades) {
	$numorden += 1;
	$articulo = mysqli_query($con, "select nombre from articulos WHERE id = '$idarticulo'");
	$row = mysqli_fetch_assoc($articulo);
	$nom_cookie="cesta_de_".$username;

	$precio = mysqli_query($con, "select precio from articulos WHERE id = '$idarticulo'");
	$row2 = mysqli_fetch_assoc($precio);
	$precio1 = $row2["precio"];
	$preciototal = $precio1 * $unidades;

	$sql2 = "INSERT INTO lineapedido (numpedido, numorden, codarticulo, cantidad, precio) 
             VALUES('$numpedido', '$numorden', '$idarticulo', '$unidades', '$preciototal')";
	mysqli_query($con, $sql2);
	//se elimina la cookie después de añadir el artículo al pedido
	setcookie($nom_cookie."[".$idarticulo."]",'', time() - 100);
}

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>

	<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>

<br>
<p>Pedido realizado correctamente. Gracias por su compra <b> <?php echo $nombre ?></b></p>
<br>

<?php
//Por último incluimos el pie
include("pie.php");
?>
