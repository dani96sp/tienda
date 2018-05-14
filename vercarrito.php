<?php
$username = 'invitado';
if (isset ($_SESSION['login_user'])) {
	$username = $_SESSION['login_user'];
}

if (!isset ($_COOKIE["cesta_de_".$username])) {
		echo "<h1>El carrito está vacío";
	}
	else {
		//Llamamos a la función mostrarCarrito()
		mostrarCarritoSimple($username);
	}

?>
<br/>

