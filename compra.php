<?php
@session_start(); // starting session
$ref = key($_REQUEST);
$username = 'invitado';

if (isset ($_SESSION['login_user'])) {
	$username = $_SESSION['login_user'];
}

$nom_cookie="cesta_de_".$username;

//Creamos las cookies y redirigimos al carrito
if($_REQUEST[$ref] == 'Comprar' || $_REQUEST[$ref] == '+') {
	if (isset ($_COOKIE["cesta_de_".$username][$ref])) {
		$unidades = $_COOKIE["cesta_de_".$username][$ref];
		setcookie($nom_cookie."[".$ref."]",$unidades + 1, time() + 2600000);
	} else {
		$nom_cookie="cesta_de_".$username;
		setcookie($nom_cookie."[".$ref."]",1, time() + 2600000);
	}

} else if($_REQUEST[$ref] == '-'){
	if ($_COOKIE["cesta_de_".$username][$ref] > 1) {
		$unidades = $_COOKIE["cesta_de_".$username][$ref];
		setcookie($nom_cookie."[".$ref."]",$unidades - 1, time() + 2600000);
	} else {
		setcookie($nom_cookie."[".$ref."]",'', time() - 100);
	}
}


header("Location: ". $_SERVER['HTTP_REFERER']);

?>
