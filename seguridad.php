<?php
//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO 
if (!isset ($_SESSION["login_user"])) { 
   	//si no existe, envio a la página de autentificacion 
   	header("Location: index.php"); 
   	//ademas salgo de este script 
   	exit(); 
}
