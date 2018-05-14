<?php
$titulo_pagina = "Índice";
$descripcion = "indice";
$keywords = "indice, palabras clave, keywords";

//Incluimos las funciones
include("funciones.php");

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
		<h1>
        <p>
            Bienvenido a la tienda de Daniel Muñoz de 2ºS
        </p>
        <p>
            <a href="ofertas.php">¡Hecha un vistazo a nuestras ofertas!</a>
        </p>
		<p>		
		Para navegar por los artículos te recomiendo ir a la categoría que quieras en el lateral izquierdo.
		</p>
		<p>		
		Además dispones de un buscador en el lateral derecho para que busques si tenemos lo que estás buscando rápidamente.
		</p></h1>
<?php
//Por último incluimos el pie
include("pie.php");
?>
