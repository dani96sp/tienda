<?php
$titulo_pagina = "Confirmar Carrito";
$descripcion = "carrito invitado";
$keywords = "carrito, palabras clave, keywords";
require("funciones.php");
//require("seguridad.php");

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
