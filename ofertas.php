<?php
$titulo_pagina = "Artículos en Oferta";
$descripcion = "ofertas";
$keywords = "ofertas, articulos, palabras clave, keywords";

//Iniciamos con la variable del número de filas en 5
//para que muestre los artículos de 5 en 5
$num_filas = 5;


//Si se ha especificado un orden lo recogemos en la variable $orden
if (isset($_GET['orden'])) {
    $orden = $_GET['orden'];
} else {
    //Si no se ha especificado un orden le damos por defecto el orden 'precio'
    $orden ='precio';
}


//Si no se ha especificado un desplazamiento le damos
//por defecto el desplazamiento 0
if (isset($_GET["desplazamiento"]))
	$desplazamiento = $_GET["desplazamiento"];
else $desplazamiento = 0;

//Número de páginas
$prevpag = $desplazamiento / 5;
$currpag = $desplazamiento / 5 + 1;
$nextpag = $desplazamiento / 5 + 2;



//Incluimos las funciones
include("funciones.php");

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
?>
	<h1><?php echo parametro_plantilla("titulo_pagina"); echo " - Página $currpag"?></h1>
<table>
<tr id='titulo'>
<td><b>Imagen</b></td>
<td><b>Nombre de Artículo</b></td>
<td><b>Descripción</b></td>
<td><b>Precio</b></td>
<td><b>Oferta</b></td>
<td><b>Añadir al carrito</b></td>
<form name="ordenar" id="ordenar" action="ofertas.php" method="get">
<h1><label for="orden">Ordenar por:</label>
<select name="orden" id="orden">
 <option value="precio">Precio</option>
 <option value="nombre">Nombre</option>
</select>
  <input type="hidden" name="categoria" value="<?php echo $categoria ?>">
  <input type="hidden" name="desplazamiento" value="<?php echo $desplazamiento?>" />
  <button type="submit">Enviar</button>
</form>
<form name="carrito" id="carrito" action="compra.php" method="POST">
<?php
//Llamada a la función mostrarArticulosOferta()
mostrarArticulosOferta($orden);
echo "<br/></form>";
echo "<br/><br/>";

//Por último se añade el pie
include("pie.php");
?>
