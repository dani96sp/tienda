<?php
$titulo_pagina = "Administrar Categorías";
$descripcion = "administrar categorías";
$keywords = "administrar, categorias, palabras clave, keywords";

//Inluimos las funciones
include("funciones.php");
include("seguridad.php");

//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");

// Se muestra solo en el caso en el que se le de a añadir categoría
if (isset($_GET['addcategoria'])) {
?>
	<fieldset>
	<legend>Categoría nueva</legend>
	<form name="formulario" id="formulario" action="administrarcategorias.php?addcategoria" method="post">
<?php
	if(!isset($_REQUEST['enviar'])){
	//Dejamos los campos en blanco por defecto
?>
	  <label for="nombre" id="correcto"><h1>Nombre de la categoría:</h1></label>
	  <input type="text" name="nombre" id="nombre" maxlength="50"/>
<?php
	} else {
		// Si hay submit se intenta agregar la categoría y comprueba que no exista ya
		agregarCategoria($_POST['nombre']);
?>
	  <label for="nombre" id="correcto"><h1>Nombre de la categoría:</label>
	  <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $_POST['nombre'] ?>"/></h1>
<?php
	}
?>
	  <input type="submit" name="enviar" id="enviar" value="Enviar"/>
	  <input type="reset" name="limpiar" id="button" value="Restablecer datos" /><br/>
	</form>
	</fieldset>

<?php
}

// Mostramos el contenido de modificar una categoria
if (isset($_GET['modcategoria'])) {
	$nomCategoria = $_GET['modcategoria'];
?>
	<fieldset>
	<legend>Categoría nueva</legend>
	<form name="formulario" id="formulario" action="administrarcategorias.php?modcategoria=<?php echo $nomCategoria ?>" method="post">
<?php
	if(!isset($_REQUEST['enviar'])){
	//Dejamos los campos en blanco por defecto
?>
	  <label for="nombre" id="correcto"><h1>Nombre de la categoría:</label>
	  <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $nomCategoria ?>"/></h1>
<?php
	} else {
		// Si hay submit se intenta modificar la categoría y comprueba que no exista ya etc
		modificarCategoria($nomCategoria, $_POST['nombre']);
?>
	  <label for="nombre" id="correcto"><h1>Nombre de la categoría:</label>
	  <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $_POST['nombre'] ?>"/></h1>
<?php
	}
?>
	  <input type="submit" name="enviar" id="enviar" value="Enviar"/>
	  <input type="reset" name="limpiar" id="button" value="Restablecer datos" /><br/>
	</form> 
	</fieldset>

<?php
}

// Mostramos el contenido de eliminar una categoria
if (isset($_GET['delcategoria'])) {
	$nomCategoria = $_GET['delcategoria'];

	if(isset($_REQUEST['enviar'])){
		eliminarCategoria($nomCategoria);
	} else {
		echo "<h1>¿Está seguro de que quiere borrar la categoría $nomCategoria ?</h1>";
		echo "Se desvincularán todos los artículos con esta categoría, aunque no afectará a los artículos.<br/>";
		echo "Una vez borrada la categoría no hay vuelta atrás.<br/>";
		echo '<form name="formulario" id="formulario" action="administrarcategorias.php?delcategoria='. $nomCategoria .'" method="post">';
		echo '<input type="submit" name="enviar" id="enviar" value="Enviar"/></form>';
	}
?>




<?php
}

// Se muestra siempre que no se le haya dado a una de estas opciones
if(!(isset($_GET['modcategoria']) || isset($_GET['delcategoria']) || isset($_GET['addcategoria']))){

?>

	<a href="administrarcategorias.php?addcategoria"><button>Añadir nueva categoría</button></a><br />
	<table>
	<tr id='titulo'>
	<td><b>Categoría</b></td>
	<td><b>Modificar</b></td>
	<td><b>Eliminar</b></td>
<?php
	mostrarPanelCategorias();
}

//Por último añadimos el pie
include("pie.php");
?>
