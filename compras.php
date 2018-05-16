<?php
$titulo_pagina = "Mis compras";
$descripcion = "mis compras";
$keywords = "mis, compras, palabras clave, keywords";

//Incluimos las funciones
include("funciones.php");
require("seguridad.php");

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
		<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>
<table>
<tr id='titulo'>
<td><b>Pedido</b></td>
<td><b>Orden</b></td>
<td><b>Fecha</b></td>
<td><b>Contenido</b></td>
<td><b>Cantidad</b></td>
<td><b>Precio</b></td>
<td><b>Estado</b></td>
<?php
//Llamamos a la función mostrarCompras()
//para que monte la tabla con los artículos
$preciototal = mostrarCompras();

//Mostramos el total de la página
echo "<h1>TOTAL: $preciototal €</h1>";

//Por último incluimos el pie
include("pie.php");
?>
