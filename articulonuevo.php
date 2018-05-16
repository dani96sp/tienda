<?php
$titulo_pagina = "Añadir nuevo artículo";
$descripcion = "añadir nuevo articulo";
$keywords = "nuevo, articulo, palabras clave, keywords";
//Incluimos las funciones
include("funciones.php");
require("seguridad.php");
//Incluimos la cabecera
include("cabecera.php");
$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
//Si se ha enviado el formulario revisamos los datos introducidos
if(isset($_REQUEST['enviar'])){
	$articulo = $_POST["nombre"];
	$precio = $_POST["precio"];
	$descripcion = $_POST["descripcion"];
	if (isset($_POST["oferta"])) {
		$oferta = $_POST["oferta"];
	} else {
		$oferta = 'no';
	}
	if (isset($_POST["stock"])) {
		$stock = $_POST["stock"];
	} else {
		$stock = 'no';
	}
	
	//validar campos
	$er_precio = "/^[0-9]+(\.[0-9]{2})?$/";

	if (!preg_match($er_precio, $precio)) {
		$precio = "";
	}

	if(empty($precio)) {
		echo "<h1>El formato del precio es incorrecto</h1>";
	} else {
		if (!isset($_POST['categoria'])) {
			echo "Selecciona al menos una categoría por favor.";
		} else {
			//Comprobamos que no exista ya un artículo con el mismo nombre
			$codigo = "SELECT * FROM articulos WHERE nombre = '$articulo'";
			$result = mysqli_query($con, $codigo);
			$rows = mysqli_num_rows($result);
			if($rows == 1) {
				echo "<h1>El articulo $articulo ya existe.</h1>";
				$articulo = "";
			} else {
				$codigo = "SELECT * FROM articulos";
				$result = mysqli_query($con, $codigo);
				$rows = mysqli_num_rows($result);
				$id = $rows + 1;
				$dir_subida = '/data/web/e86904/html/tienda/articulos/';
				$fichero_subido = $dir_subida . $id.'.png';
				if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
					// si todo es correcto se crea el articulo y se redirige a articulos.php
					$sql = "INSERT INTO articulos (nombre, descripcion, precio, oferta, stock) VALUES" .
					"('$articulo', '$descripcion', '$precio', '$oferta', '$stock')";	
					mysqli_query($con, $sql);
					foreach($_POST['categoria'] as $categoria) {
						$sql = "INSERT INTO categorias (articulo, nombre) VALUES ('$id','$categoria')";
						if(!mysqli_query($con, $sql)) {
							echo "Algo ha fallado al insertar la categoría $categoria del articulo $id en base de datos";
						}
					}
					echo "<h1>Artículo insertado correctamente</h1>";
				} else { // Algo ha fallado con la imagen
					switch ($_FILES['imagen']['error']) {
					    case 1:
							echo "La imagen tiene un tamaño demasiado grande.";
					        break;
					    case 2:
					        echo "La imagen tiene un tamaño demasiado grande.";
					        break;
					    case 4:
					        echo "Suba una imagen por favor.";
					        break;
					    default:
					        echo "Error al subir la imagen, intentelo de nuevo por favor.";
					}
				}
			}
		}

	}
}
?>
<fieldset>
<legend>Artículo nuevo</legend>
<form name="formulario" id="formulario" action="articulonuevo.php" method="post" enctype="multipart/form-data">
<div class="categorias">
	<h1>Categorías:</h1>

<?php
    mostrarCategoriasArticulo();
?>
</div>
<?php
if(!isset($_REQUEST['enviar'])){
//Dejamos los campos en blanco por defecto
?>
  <label for="nombre" id="correcto"><h1>Nombre del artículo:</label>
  <input type="text" name="nombre" id="nombre" maxlength="50"/></h1>
  <label for="precio" id="correcto"><h1>Precio:</label>
  <input type="text" name="precio" id="precio" maxlength="50"/></h1>
  <label for="descripcion" id="correcto"><h1>Descripción:</h1></label>
  <textarea name="descripcion" form="formulario"></textarea><br/>

<h1><input type="checkbox" name="oferta" value="si">Oferta</h1>
<h1><input type="checkbox" name="stock" value="si" checked>Stock</h1>

<?php
} else {
//Si se han enviado datos los rellenamos con lo que se haya enviado si son correctos para ayudar
?>
  <label for="nombre" id="correcto"><h1>Nombre del artículo:</label>
  <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $articulo ?>"/></h1>
  <label for="precio" id="correcto"><h1>Precio:</label>
  <input type="text" name="precio" id="precio" maxlength="50" value="<?php echo $precio ?>"/></h1>
  <label for="descripcion" id="correcto"><h1>Descripción:</h1></label>
  <textarea name="descripcion" form="formulario"><?php echo $descripcion ?></textarea><br/>
<?php
	if ($oferta == 'si') {
		echo '<h1><input type="checkbox" name="oferta" value="si" checked>Oferta</h1>';
	} else {
		echo '<h1><input type="checkbox" name="oferta" value="si">Oferta</h1>';
	}
	if ($stock == 'si') {
		echo '<h1><input type="checkbox" name="stock" value="si" checked>Stock</h1>';
	} else {
		echo '<h1><input type="checkbox" name="stock" value="si">Stock</h1>';
	}
}
?>
  <h1>Imagen:
  <input type="hidden" name="MAX_FILE_SIZE" VALUE="900000">
  <input type="file" name="imagen"></h1><br/>
  <input type="submit" name="enviar" id="enviar" value="Enviar"/>
  <input type="reset" name="limpiar" id="button" value="Restablecer datos" /><br/>
</form> 
</fieldset>
<?php
//Por último añadimos el pie
include("pie.php");
?>
