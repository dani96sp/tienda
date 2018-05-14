<?php
$titulo_pagina = "Mi perfil";
$descripcion = "Perfil";
$keywords = "perfil, palabras clave, keywords";

//Inluimos las funciones
include("funciones.php");
require("seguridad.php");
//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
		<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>
		Usuario: <?php echo $username ?><br/>
		Nombre: <?php echo $nombre ?><br/>
		Teléfono: <?php echo $telefono ?><br/>
		E-mail: <?php echo $email ?><br/>
		Tipo de cuenta: <?php echo $tipo ?><br/>
		<a href="usuarioeditar.php"><button>Editar Datos Personales</button></a><br/>
		<a href="cambiarpassword.php"><button>Modificar la contraseña</button></a>

<?php
//Por último incluimos el pie
include("pie.php");
?>
