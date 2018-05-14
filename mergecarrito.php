<?php

require("seguridad.php");
session_start();
$username = $_SESSION['login_user'];
$nom_cookie="cesta_de_".$username;

if(!isset($_GET["carrito"])) {
	header("Location: confirmarcarrito.php");
}else {
	if ($_GET["carrito"] == 'invitado') {
		// Usamos la cesta de invitado
		// Borramos primero la cookie del usuario
		foreach ($_COOKIE[$nom_cookie] as $idarticulo => $unidades) {
			setcookie($nom_cookie."[".$idarticulo."]",'', time() - 100);
		}

		foreach ($_COOKIE["cesta_de_invitado"] as $idarticulo => $unidades) {
			// Cogemos los articulos y la cantidad de las cookies de invitado
			// y lo metemos en la cookie del usuario

			setcookie($nom_cookie."[".$idarticulo."]",$unidades, time() + 2600000);
			setcookie("cesta_de_invitado[".$idarticulo."]",'', time() - 100);
		}
	} else if ($_GET["carrito"] == 'usuario') {
		// Borramos la cookie del invitado
		foreach ($_COOKIE['cesta_de_invitado'] as $idarticulo => $unidades) {
			setcookie("cesta_de_invitado"."[".$idarticulo."]",'', time() - 100);
		}
	}
header("Location: carrito.php");
exit;
}

?>
