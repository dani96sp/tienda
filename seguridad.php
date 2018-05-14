<?php
//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO 
if (!isset ($_SESSION["login_user"])) { 
   	//si no existe, envio a la página de autentificacion 
   	header("Location: index.php"); 
   	//ademas salgo de este script 
   	exit(); 
}
if(isset($_SESSION["login_user"]) && isset($_COOKIE["cesta_de_invitado"])) {
	//si hay una cesta de invitado reenvio a la pagina de confimacion
   	header("Location: confirmarcarrito.php"); 
   	//ademas salgo de este script 
   	exit(); 
}
?>
