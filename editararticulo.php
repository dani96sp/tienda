<?php 
$titulo_pagina = "Editar Artículo";
$descripcion = "editar articulo";
$keywords = "editar, articulo, palabras clave, keywords";

//Inluimos las funciones
include("funciones.php");
include("seguridad.php");
//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");
$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
//acentos
$con->query("SET NAMES 'utf8'");

//Guardamos el usuario
$username = $_SESSION['login_user'];
if(!isset($_REQUEST['enviar'])){
	$id = $_GET['articulo'];
} else {
	$id = $_REQUEST["id"];
}

$sql = "SELECT * FROM articulos WHERE id = '$id'";
if ($result = mysqli_query($con, $sql)) {
	while ($row = mysqli_fetch_assoc($result)) {
		$nombre = $row["nombre"];
		$descripcion = $row["descripcion"];
		$precio = $row["precio"];
		$oferta = $row["oferta"];
		$stock = $row["stock"];
	}
	mysqli_free_result($result);
}

if(isset($_REQUEST['enviar'])){
	$nombre = $_POST["nombre"];
	$descripcion = $_POST["descripcion"];
	$precio = $_POST["precio"];
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

	} else if (!isset($_POST['categoria'])) {
		echo "Selecciona al menos una categoría por favor.";
	} else {// si todo es correcto modificamos y le indicamos al usuario que todo ha ido correctamente
		if ($_FILES['imagen']['error'] == 4) {
			// si no se toca la imagen
			$sql = "UPDATE articulos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', oferta = '$oferta', stock = '$stock' WHERE id = '$id'";
			mysqli_query($con, $sql);
			// borramos las categorías antes de añadir las nuevas con cambios
			$sql = "DELETE FROM categorias WHERE articulo='$id'";
			mysqli_query($con, $sql);
			foreach($_POST['categoria'] as $categoria) {
				$sql = "INSERT INTO categorias (articulo, nombre) VALUES ('$id','$categoria')";
				if(!mysqli_query($con, $sql)) {
					echo "Algo ha fallado al insertar la categoría $categoria del articulo $id en base de datos";
				}
			}
			echo "<h1><i>Cambios guardados</i></h1>";
		} else {
			// si se sube una imagen
			if ($_FILES['imagen']['error'] == 0){ // no hay error en la imagen
				$dir_subida = '/data/web/e86904/html/tienda/articulos/';
				$fichero_subido = $dir_subida . $id.'.png';
				unlink($fichero_subido);
				if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
					// si todo es correcto se modifica el articulo
					$sql = "UPDATE articulos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', oferta = '$oferta', stock = '$stock' WHERE id = '$id'";
					mysqli_query($con, $sql);
					// borramos las categorías antes de añadir las nuevas con cambios
					$sql = "DELETE FROM categorias WHERE articulo='$id'";
					mysqli_query($con, $sql);
					foreach($_POST['categoria'] as $categoria) {
						$sql = "INSERT INTO categorias (articulo, nombre) VALUES ('$id','$categoria')";
						if(!mysqli_query($con, $sql)) {
							echo "Algo ha fallado al insertar la categoría $categoria del articulo $id en base de datos";
						}
					}
					echo "<h1><i>Cambios guardados, refresca caché para ver la imagen actualizada</i></h1>";
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
?>


<h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>

<fieldset>
<legend>Modificar datos</legend>
<form name="formulario" id="formulario" action="editararticulo.php" method="post" enctype="multipart/form-data">
	<div class="categorias">
	<h1>Categorías:</h1>

<?php
	mostrarCategoriasArticuloModificado($id);
?>
</div>
  <label for="nombre" id="correcto"><h1>Nombre del artículo:</label>
  <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $nombre ?>"/></h1>
  <label for="precio" id="correcto"><h1>Precio:</label>
  <input type="text" name="precio" id="precio" maxlength="50" value="<?php echo $precio ?>"/></h1>
	<input name="id" type=hidden id="id" value="<?php echo $id ?>"/>
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

?>
  <h1>Imagen:
  <br />
  <?php
  echo "<a href='articulos/$id.png'><img src='articulos/$id.png' width='50px' height='50px'></a>";
  ?>
  <br />
  <input type="hidden" name="MAX_FILE_SIZE" VALUE="900000">
  <input type="file" name="imagen"></h1><br/>
  <input type="submit" name="enviar" id="enviar" value="Guardar datos"/>
  <input type="reset" name="limpiar" id="button" value="Restablecer datos" ><br/>
</form> 
</fieldset>

<?php
echo "<br/><br/>";

//Por último incluimos el pie
include("pie.php");
?>
