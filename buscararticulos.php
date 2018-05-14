<?php
$titulo_pagina = "Buscar articulos";
$descripcion = "buscar articulos";
$keywords = "buscar, articulos, palabras clave, keywords";

//Incluimos las funciones
require("funciones.php");

//Dejamos el orden por defecto en precio
$orden ='precio';

//Variable que contendrá el resultado de la búsqueda
$texto = '';

//Variable que contendrá el número de registros encontrados
$registros = '';

if($_GET){
	$busqueda = trim($_GET['buscar']);
	$entero = 0;

	if (empty($busqueda)){
		$texto = 'Búsqueda sin resultados';
	}else{
		//Si hay información para buscar, abrimos la conexión
		$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
		//acentos
        $con->query("SET NAMES 'utf8'");

		//Consulta para la base de datos
		$sql = "SELECT * FROM articulos WHERE stock = 'si' AND nombre LIKE '%" .$busqueda. "%' ORDER BY $orden";

		//Ejecución de la consulta
		$resultado = mysqli_query($con, $sql);

		//Si hay resultados...
		if (mysqli_num_rows($resultado) > 0){ 

			//Se recoge el número de resultados
			$registros = '<p>Se han encontrado ' . mysqli_num_rows($resultado) . ' artículos que coinciden con su búsqueda </p>';

			//Se almacenan las cadenas de resultado
			while($row = mysqli_fetch_assoc($resultado)){ 
				$id = $row["id"];

				$texto .= "</td></tr><tr><td><a href='articulos/$id.png'><img src='articulos/" .
				$row["id"] . ".png' width='50px' height='50px'></a>" . "</td><td>". $row["nombre"] .
				"</td><td>" . $row["descripcion"] . "</td><td>" . $row["precio"] . "€" . "</td><td>" .
				$row["oferta"] .  "</td>" . "<td><input type='submit' name='" . $row["id"] . "' id='" .
				$row["id"] . "' value='Comprar'>";
			}
			mysqli_close($con);
		}else{
			$texto = "No hay artículos que coincidan con su búsqueda";	
		}
	}
}


//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
	<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1> 
<form id="buscador" name="buscador" method="GET" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
    <input id="buscar" name="buscar" type="search" placeholder="Buscar aquí..." autofocus >
    <input type="submit" value="buscar">
</form>
<?php 
//Resultado y número de registros.
echo $registros;
?>
<form name="carrito" id="carrito" action="compra.php" method="POST">
<table>
<tr id='titulo'>
<td><b>Imágen</b></td>
<td><b>Nombre de Artículo</b></td>
<td><b>Descripción</b></td>
<td><b>Precio</b></td>
<td><b>Oferta</b></td>
<td><b>Añadir al carrito</b></td>

<?php
//Contenido de la búsqueda
echo $texto;
?>
</td></tr></table><br>
</form>

<?php
echo "<br/><br/>";

//Por último se añade el pie
include("pie.php");
?>
