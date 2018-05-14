<?php
$titulo_pagina = "Empleados";
$descripcion = "empleados";
$keywords = "empleados, palabras clave, keywords";

//Inluimos las funciones
include("funciones.php");
require("seguridad.php");

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
		<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>
<table>
<tr id='titulo'>
<td><b>Nombre de Usuario</b></td>
<td><b>Nombre completo</b></td>
<td><b>Teléfono</b></td>
<td><b>E-mail</b></td>
<td id='editar'><b>Editar</b></td>
<?php
//Llamamos a la función mostrarClientes()
//para que monte la tabla con los clientes
mostrarEmpleados();
?>
</td></tr></table><br>

<?php
//Por último incluimos el pie
include("pie.php");
?>
