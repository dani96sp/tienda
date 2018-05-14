<?php
$titulo_pagina = "Mi carrito de la compra";
$descripcion = "carrito";
$keywords = "carrito, palabras clave, keywords";
require("funciones.php");
//require("compra.php");

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
  <h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>

<form action="compra.php" method="POST">
<?php
//Llamamos a la función mostrarCarrito()
mostrarCarrito($username);
echo "<br/><br/>";
//Por último incluimos el pie
include("pie.php");
?>