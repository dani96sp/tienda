<?php
$titulo_pagina = "Confirmar Carrito";
$descripcion = "carrito invitado";
$keywords = "carrito, palabras clave, keywords";
require("funciones.php");
//require("seguridad.php");
//require("compra.php");

//Iniciamos con la variable del número de filas en 5
//para que muestre los artículos de 5 en 5
$num_filas = 5;

//Si no se ha especificado un orden le damos
//por defecto el orden 'precio'
if(!isset($orden)){
	$orden ='precio';
}

//Si no se ha especificado un desplazamiento le damos
//por defecto el desplazamiento 0
if (isset($_GET["desplazamiento"]))
	$desplazamiento = $_GET["desplazamiento"];
else $desplazamiento = 0;

//Número de páginas
$prevpag = $desplazamiento / $num_filas;
$currpag = $prevpag +  1;
$nextpag = $prevpag +  2;

//Si se ha espeficiado un orden lo recogemos en la variable $orden
if (isset($_GET['orden'])) {
	$orden = $_GET['orden'];
}

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
  <h1>¿Con qué carrito quiere quedarse?</h1>
<?php
//Llamamos a la función mostrarCarrito()
echo "<a href='mergecarrito.php?carrito=usuario'>Carrito de su sesión</a><br/>";
mostrarCarrito($username);
echo "<br/><br/>";


$invitado = 'invitado';
echo "<a href='mergecarrito.php?carrito=invitado'>Carrito sin sesión</a><br/>";
mostrarCarrito($invitado);
echo "<br/><br/>";

//Por último incluimos el pie
include("pie.php");
?>
