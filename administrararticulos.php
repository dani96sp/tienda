<?php
$titulo_pagina = "Administrar Artículos";
$descripcion = "administrar articulos";
$keywords = "administrar, articulos, palabras clave, keywords";

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
$prevpag = $desplazamiento / $num_filas;
$currpag = $prevpag +  1;
$nextpag = $prevpag +  2;

//Incluimos las funciones
include("funciones.php");
include("seguridad.php");
//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");

// Comprobamos si se ha hecho submit
$submit = (isset($_REQUEST['enviar'])) ? true : false;

// Comprobamos el parámetro que se pasa
$option = 'def';
//TODO
if (isset($_REQUEST['addarticulo'])) {
    $option = 'add';
} else if (isset($_REQUEST['modarticulo'])) {
    $option = 'mod';
    $idarticulo = $_REQUEST['modarticulo'];
}

switch ($option) {
    case 'add':
    // Se muestra solo en el caso en el que se le de a añadir artículo
        if($submit) {
            $con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
            $articulo = $_POST["nombre"];
            $precio = $_POST["precio"];
            $descripcion = $_POST["descripcion"];
            $oferta = (isset($_POST["oferta"])) ? 'si' : 'no';
            $stock = (isset($_POST["stock"])) ? 'si' : 'no';

            //validar campos
            $er_precio = "/^[0-9]+(\.[0-9]{2})?$/";
            if (!preg_match($er_precio, $precio)) {
                $precio = "";
            }

            if(empty($precio)) {
                echo "<h1>El formato del precio es incorrecto</h1>";
            } else if (!isset($_POST['categoria'])) {
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
                    $idarticulo = $rows + 1;
                    $dir_subida = '/data/web/e86904/html/tienda/articulos/';
                    $fichero_subido = $dir_subida . $idarticulo . '.png';
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
                        // Se inserta el artículo
                        $sql = "INSERT INTO articulos (nombre, descripcion, precio, oferta, stock) VALUES" .
                            "('$articulo', '$descripcion', '$precio', '$oferta', '$stock')";
                        mysqli_query($con, $sql);
                        foreach ($_POST['categoria'] as $categoria) {
                            $sql = "INSERT INTO categorias (articulo, nombre) VALUES ('$idarticulo','$categoria')";
                            if (!mysqli_query($con, $sql)) {
                                echo "Algo ha fallado al insertar la categoría $categoria del articulo en base de datos";
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
        if ($submit) {
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
        } else {
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
        break;
    case 'mod':
    // Mostramos el contenido de modificar un articulo
        $con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
        //acentos
        $con->query("SET NAMES 'utf8'");

        //Guardamos el usuario
        $username = $_SESSION['login_user'];

        $sql = "SELECT * FROM articulos WHERE id = '$idarticulo'";
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

        if ($submit) {
            $nombre = $_POST["nombre"];
            $descripcion = $_POST["descripcion"];
            $precio = $_POST["precio"];
            $oferta = (isset($_POST["oferta"])) ? 'si' : 'no';
            $stock = (isset($_POST["stock"])) ? 'si' : 'no';

            //validar campos
            $er_precio = "/^[0-9]+(\.[0-9]{2})?$/";

            if (!preg_match($er_precio, $precio)) {
                $precio = "";
            }

            if (empty($precio)) {
                echo "<h1>El formato del precio es incorrecto</h1>";
            } else if (!isset($_POST['categoria'])) {
                echo "Selecciona al menos una categoría por favor.";
            } else {// si todo es correcto modificamos y le indicamos al usuario que todo ha ido correctamente
                if ($_FILES['imagen']['error'] == 4) {
                    // si no se toca la imagen
                    $sql = "UPDATE articulos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', oferta = '$oferta', stock = '$stock' WHERE id = '$idarticulo'";
                    mysqli_query($con, $sql);
                    // borramos las categorías antes de añadir las nuevas con cambios
                    $sql = "DELETE FROM categorias WHERE articulo='$idarticulo'";
                    mysqli_query($con, $sql);
                    foreach ($_POST['categoria'] as $categoria) {
                        $sql = "INSERT INTO categorias (articulo, nombre) VALUES ('$idarticulo','$categoria')";
                        if (!mysqli_query($con, $sql)) {
                            echo "Algo ha fallado al insertar la categoría $categoria del articulo en base de datos";
                        }
                    }
                    echo "<h1><i>Cambios guardados</i></h1>";
                } else {
                    // si se sube una imagen
                    if ($_FILES['imagen']['error'] == 0) { // no hay error en la imagen
                        $dir_subida = '/data/web/e86904/html/tienda/articulos/';
                        $fichero_subido = $dir_subida . $idarticulo . '.png';
                        unlink($fichero_subido);
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
                            // si todo es correcto se modifica el articulo
                            $sql = "UPDATE articulos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', oferta = '$oferta', stock = '$stock' WHERE id = '$idarticulo'";
                            mysqli_query($con, $sql);
                            // borramos las categorías antes de añadir las nuevas con cambios
                            $sql = "DELETE FROM categorias WHERE articulo='$idarticulo'";
                            mysqli_query($con, $sql);
                            foreach ($_POST['categoria'] as $categoria) {
                                $sql = "INSERT INTO categorias (articulo, nombre) VALUES ('$idarticulo','$categoria')";
                                if (!mysqli_query($con, $sql)) {
                                    echo "Algo ha fallado al insertar la categoría $categoria del articulo en base de datos";
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
            <form name="formulario" id="formulario" action="administrararticulos.php?modarticulo=<?php echo $idarticulo ?>" method="post" enctype="multipart/form-data">
                <div class="categorias">
                    <h1>Categorías:</h1>
<?php
                    mostrarCategoriasArticuloModificado($idarticulo);
?>
                </div>
                <label for="nombre" id="correcto"><h1>Nombre del artículo:</label>
                <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $nombre ?>"/></h1>
                <label for="precio" id="correcto"><h1>Precio:</label>
                <input type="text" name="precio" id="precio" maxlength="50" value="<?php echo $precio ?>"/></h1>
                <input name="id" type=hidden id="id" value="<?php echo $idarticulo ?>"/>
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
                    echo "<a href='articulos/$idarticulo.png'><img src='articulos/$idarticulo.png' width='50px' height='50px'></a>";
?>
                    <br />
                    <input type="hidden" name="MAX_FILE_SIZE" VALUE="900000">
                    <input type="file" name="imagen"></h1><br/>
                <input type="submit" name="enviar" id="enviar" value="Guardar datos"/>
                <input type="reset" name="limpiar" id="button" value="Restablecer datos" ><br/>
            </form>
        </fieldset>
        <br/><br/>
<?php
        break;
    default:
    // Se muestra siempre que no se le haya dado a una de esas opciones
?>
<h1><?php echo parametro_plantilla("titulo_pagina"); echo " - Página $currpag"?></h1>
<table>
    <tr id='titulo'>
        <td><b>Imagen</b></td>
        <td><b>Nombre de Artículo</b></td>
        <td><b>Descripción</b></td>
        <td><b>Precio</b></td>
        <td><b>Oferta</b></td>
        <td><b>Stock</b></td>
        <td><b>Categoría</b></td>
        <td><b>Modificar</b></td>
        <form name="ordenar" id="ordenar" action="administrararticulos.php" method="get">
            <h1><label for="orden">Ordenar por:</label>
                <select name="orden" id="orden">
                    <option value="precio">Precio</option>
                    <option value="nombre">Nombre</option>
                </select>
                <input type="hidden" name="desplazamiento" value="<?php echo $desplazamiento?>" />
                <button type="submit">Enviar</button></h1>
        </form>
        <a href="administrararticulos.php?addarticulo"><button>Añadir nuevo artículo</button></a>
<?php
        //Llamamos a la función mostrarArticulos()
        //para que monte la tabla con los artículos
        mostrarArticulos($orden, $desplazamiento, $num_filas);

} // fin del switch


//Por último añadimos el pie
include("pie.php");
?>
