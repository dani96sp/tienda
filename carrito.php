<?php
$titulo_pagina = "Mi carrito de la compra";
$descripcion = "carrito";
$keywords = "carrito, palabras clave, keywords";
require("funciones.php");
//require("compra.php");
if(isset($_SESSION["login_user"]) && isset($_COOKIE["cesta_de_invitado"])) {
    //si hay una cesta de invitado reenvio a la pagina de confimacion
    header("Location: confirmarcarrito.php");
    //ademas salgo de este script
    exit();
}
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