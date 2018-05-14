<?php
$titulo_pagina = "Finalizando la compra";
$descripcion = "finalizando la compra";
$keywords = "finalizando, la, compra, palabras clave, keywords";

//Inluimos las funciones
require("funciones.php");

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>

<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>
<br/>

<?php
if(isset($_SESSION['login_user'])){
	echo "<a href='comprafinalizada.php'><button>Realizar compra</button></a>";
}
else {
	echo "<h1>Debe entrar en su cuenta antes de poder realizar el pedido.</h1>";
	echo "<h1><a href='usuarionuevo.php'>Si no tiene una cuenta haga click aquí para registrarse</a></h1>";
}
include("pie.php");
?>
