<?php
$titulo_pagina = "Pedidos";
$descripcion = "pedidos";
$keywords = "pedidos, palabras clave, keywords";

//Inluimos las funciones
include("funciones.php");
require("seguridad.php");
//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
		<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>
<table>
<tr id='titulo'>
<td><b>Pedido</b></td>
<td><b>Cliente</b></td>
<td><b>Fecha</b></td>
<td><b>Estado</b></td>
<td><b>Detalles</b></td>
<?php
//Llamamos a la función mostrarPedidos()
//para que monte la tabla con los pedidos
mostrarPedidos();

//Por último incluimos el pie
include("pie.php");
?>
