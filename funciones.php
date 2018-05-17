<?php
session_start(); // Starting Session
define ("HOSTNAME","localhost");
define ("DATABASE","u136110db1");
define ("USER_DB","u136110db1");
define ("PASSWORD_DB","c2KOuQ3");


//La función parametro_plantilla la utilizamos
//para poner el título de la página, la descripción
//y las palabras clave en cada página 
function parametro_plantilla($variable){
	if (isset($GLOBALS[$variable])) {
		echo $GLOBALS[$variable];
	} else {
		echo "Sin dato cargado: " . $variable;
	}
}

//Mostramos los clientes
function mostrarClientes() {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$sql = "SELECT * FROM usuarios WHERE `tipo` = 'cliente'";
	//acentos
	$con->query("SET NAMES 'utf8'");
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$username = $row["username"];
			printf ("%s %s %s %s %s", "</td></tr><tr><td>" . $username, "</td><td>" . $row["nombre"],
                "</td><td>" . $row["telefono"],  "</td><td>" . $row["email"],
                "</td><td><a href='administrarclientes.php?modcliente=$username'><button>EDITAR</button></a>");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
    echo "</td></tr></table><br>";
}

//Mostramos los empleados
function mostrarEmpleados() {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$sql = "SELECT * FROM usuarios WHERE `tipo` = 'empleado'";
	//acentos
	$con->query("SET NAMES 'utf8'");
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$username = $row["username"];
			printf ("%s %s %s %s %s", "</td></tr><tr><td>" . $username, "</td><td>" . $row["nombre"], "</td><td>" . $row["telefono"],  "</td><td>" . $row["email"],
			"</td><td><a href='administrarempleados.php?modempleado=$username'><button>EDITAR</button></a>");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
	echo "</td></tr></table><br>";
}

//Mostramos los artículos
function mostrarArticulos(&$orden, $desplazamiento, $num_filas) {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$query1 = mysqli_query($con, "select * from articulos");
	$total_articulos = mysqli_num_rows($query1);
	$sql = "SELECT * FROM articulos ORDER BY $orden LIMIT $desplazamiento, $num_filas";
	//acentos
	$con->query("SET NAMES 'utf8'");
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$id = $row["id"];
			// seleccionar las categorias de cada articulo
			$sql2 = "SELECT nombre FROM categorias WHERE articulo = '$id'";

			printf ("%s %s %s %s %s %s", "</td></tr><tr><td><a href='articulos/$id.png'><img src='articulos/" . $row["id"] . ".png' width='50px' height='50px'></a>", "</td><td>". $row["nombre"], "</td><td>" . $row["descripcion"], "</td><td>" . $row["precio"],   "€</td><td><b>" . $row["oferta"] . "</b></td><td><b>", $row["stock"] . "</b></td><td>");
			//imprimimos las categorias
			if ($result2 = mysqli_query($con, $sql2)) {
				while ($row = mysqli_fetch_assoc($result2)) {
					printf ("%s", "<a href='categorias.php?categoria=" . $row["nombre"] . "'>" . $row["nombre"] . "</a> ");
				}
			}
			//y por ultimo el boton de editar dicho articulo
			printf ("%s", "</td><td><a href='administrararticulos.php?modarticulo=" . $id . "'><button>Modificar</button></a> ");

		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
	echo "</td></tr></table><br>";

	//desplazamiento
	$prevpag = $desplazamiento / $num_filas;
	$currpag = $prevpag +  1;
	$nextpag = $prevpag +  2;
	if ($desplazamiento > 0) {
		$prev = $desplazamiento - $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?orden=$orden&desplazamiento=$prev";
		echo "<a href='$url'>Página $prevpag</a>&nbsp;&nbsp;&nbsp;";
	} else {
		echo "Página 1&nbsp;&nbsp;&nbsp;";
	}

	if ($total_articulos > ($desplazamiento + $num_filas)) {
		$prox = $desplazamiento + $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?orden=$orden&desplazamiento=$prox";
		echo "<a href='$url'>Página $nextpag</a>";
	} else {
		echo "Página $currpag";
	}
    echo "<br/><br/>";
}

//Mostramos las compras del usuario logueado
function mostrarCompras() {
	$num_filas = 5;
	if (isset($_GET["desplazamiento"]))
		$desplazamiento = $_GET["desplazamiento"];
	else $desplazamiento = 0;
	$cliente = $_SESSION['login_user'];
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$query1 = mysqli_query($con, "SELECT lineapedido.* FROM pedidos, lineapedido WHERE pedidos.numpedido = lineapedido.numpedido AND cliente = '$cliente'");
	$total_articulos = mysqli_num_rows($query1);
	$sql2 = "SELECT lineapedido.numpedido, lineapedido.numorden, lineapedido.codarticulo, lineapedido.cantidad, lineapedido.precio, articulos.nombre, pedidos.fecha, pedidos.estado
	FROM lineapedido, articulos, pedidos WHERE lineapedido.numpedido = pedidos.numpedido AND lineapedido.codarticulo = articulos.id AND pedidos.cliente = '$cliente'
	ORDER BY lineapedido.numpedido DESC, lineapedido.numorden asc LIMIT $desplazamiento, $num_filas";
	//acentos
	$con->query("SET NAMES 'utf8'");

	$preciototal = 0;
	if ($result2 = mysqli_query($con, $sql2)) {
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $precio = $row2["precio"];
            $preciototal = $preciototal + $precio;
            printf("%s %s %s %s %s %s %s", "</td></tr><tr><td>" . $row2["numpedido"],
                "</td><td>" . $row2["numorden"], "</td><td>" . $row2["fecha"], "</td><td>" . $row2["nombre"],
                "</td><td>" . $row2["cantidad"], "</td><td>" . $row2["precio"] . "€", "</td><td>" . $row2["estado"]);
        }
        mysqli_free_result($result2);
    }
	mysqli_close($con);
	echo "</td></tr></table><br>";
	//desplazamiento
	$prevpag = $desplazamiento / $num_filas;
	$currpag = $prevpag +  1;
	$nextpag = $prevpag +  2;
	if ($desplazamiento > 0) {
		$prev = $desplazamiento - $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?desplazamiento=$prev";
		echo "<a href='$url'>Página $prevpag</a>&nbsp;&nbsp;&nbsp;";
	} else {
		echo "Página 1&nbsp;&nbsp;&nbsp;";
	}

	if ($total_articulos > ($desplazamiento + $num_filas)) {
		$prox = $desplazamiento + $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?desplazamiento=$prox";
		echo "<a href='$url'>Página $nextpag</a>";
	} else {
		echo "Página $currpag";
	}
	return $preciototal;
}

//Mostramos todos los pedidos
function mostrarPedidos() {
	$num_filas = 5;
	if (isset($_GET["desplazamiento"]))
		$desplazamiento = $_GET["desplazamiento"];
	else $desplazamiento = 0;

	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$query1 = mysqli_query($con, "SELECT * FROM pedidos");
	$total_articulos = mysqli_num_rows($query1);
	$sql = "SELECT * FROM pedidos ORDER BY numpedido DESC LIMIT $desplazamiento, $num_filas";
	//acentos
	$con->query("SET NAMES 'utf8'");
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$pedido = $row["numpedido"];
			printf ("%s %s %s %s %s", "</td></tr><tr><td>" . $row["numpedido"], "</td><td>" . $row["cliente"], "</td><td>" . $row["fecha"],
				"</td><td>" . $row["estado"], "</td><td><a href='editarpedido.php?pedido=$pedido'><button>Ver detalles</button></a>");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
	echo "</td></tr></table><br>";
	//desplazamiento
	$prevpag = $desplazamiento / $num_filas;
	$currpag = $prevpag +  1;
	$nextpag = $prevpag +  2;
	if ($desplazamiento > 0) {
		$prev = $desplazamiento - $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?desplazamiento=$prev";
		echo "<a href='$url'>Página $prevpag</a>&nbsp;&nbsp;&nbsp;";
	} else {
		echo "Página 1&nbsp;&nbsp;&nbsp;";
	}

	if ($total_articulos > ($desplazamiento + $num_filas)) {
		$prox = $desplazamiento + $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?desplazamiento=$prox";
		echo "<a href='$url'>Página $nextpag</a>";
	} else {
		echo "Página $currpag";
	}
}

//Mostramos las categorías
function mostrarCategorias() {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	//acentos
	$con->query("SET NAMES 'utf8'");
	$sql = "SELECT DISTINCT nombre FROM categorias";
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$nombre = $row["nombre"];
			printf ("%s", "<li><a href='categorias.php?categoria=$nombre'>" . $row["nombre"] . "</a></li>");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
}

//Mostramos las categorías para un articulo
function mostrarCategoriasArticulo() {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	//acentos
	$con->query("SET NAMES 'utf8'");
	$sql = "SELECT DISTINCT nombre FROM categorias";
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			printf ("%s", "<input type='checkbox' name='categoria[]' value='".$row["nombre"]."'>" . $row["nombre"] . "<br />");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
}

//Mostramos las categorías para un articulo modificado
function mostrarCategoriasArticuloModificado($articulo) {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	//acentos
	$con->query("SET NAMES 'utf8'");
	$sql = "SELECT DISTINCT nombre FROM categorias";
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$nombre = $row["nombre"];
			$sql2 = "SELECT nombre FROM categorias WHERE articulo='$articulo' and nombre='$nombre'";
			if (mysqli_num_rows(mysqli_query($con, $sql2)) == 1) {
				printf ("%s", "<input type='checkbox' name='categoria[]' value='".$row["nombre"]."' checked>" . $row["nombre"] . "<br />");
			} else {
				printf ("%s", "<input type='checkbox' name='categoria[]' value='".$row["nombre"]."'>" . $row["nombre"] . "<br />");
			}
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
}

//Mostramos los artículos según la categoría
function mostrarArticulosPorCategoria(&$orden, &$categoria) {
	$num_filas = 5;

	if (isset($_GET["desplazamiento"]))
		$desplazamiento = $_GET["desplazamiento"];
	else $desplazamiento = 0;
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$query1 = mysqli_query($con, "select DISTINCT categorias.* from categorias,articulos WHERE categorias.nombre = '$categoria' AND articulos.stock = 'si'");
	$total_articulos = mysqli_num_rows($query1);
	$sql = "SELECT DISTINCT articulos.* FROM articulos,categorias WHERE categorias.nombre = '$categoria' && articulos.id = categorias.articulo AND articulos.stock = 'si' ORDER BY $orden LIMIT $desplazamiento, $num_filas";
	//acentos
	$con->query("SET NAMES 'utf8'");
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$id = $row["id"];
			printf ("%s %s %s %s %s %s", "</td></tr><tr><td><a href='articulos/$id.png'><img src='articulos/" . $row["id"] . ".png' width='50px' height='50px'></a>", "</td><td>". $row["nombre"], "</td><td>" . $row["descripcion"], "</td><td>" . $row["precio"] . "€",  "</td><td>" . $row["oferta"],  "</td>" . "<td><input type='submit' name='" . $row["id"] . "' id='" . $row["id"] . "' value='Comprar'>");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
	echo "</td></tr></table><br>";
	//desplazamiento
	$prevpag = $desplazamiento / $num_filas;
	$currpag = $prevpag +  1;
	$nextpag = $prevpag +  2;
	if ($desplazamiento > 0) {
		$prev = $desplazamiento - $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?categoria=$categoria&orden=$orden&desplazamiento=$prev";
		echo "<a href='$url'>Página $prevpag</a>&nbsp;&nbsp;&nbsp;";
	} else {
		echo "Página 1&nbsp;&nbsp;&nbsp;";
	}

	if ($total_articulos > ($desplazamiento + $num_filas)) {
		$prox = $desplazamiento + $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?categoria=$categoria&orden=$orden&desplazamiento=$prox";
		echo "<a href='$url'>Página $nextpag</a>";
	} else {
		echo "Página $currpag";
	}
}

//Mostramos los artículos en oferta
function mostrarArticulosOferta(&$orden) {
	$num_filas = 5;

	if (isset($_GET["desplazamiento"]))
		$desplazamiento = $_GET["desplazamiento"];
	else $desplazamiento = 0;
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$query1 = mysqli_query($con, "SELECT articulos.* from articulos WHERE articulos.oferta = 'si' AND articulos.stock = 'si'");
	$total_articulos = mysqli_num_rows($query1);
	$sql = "SELECT articulos.* FROM articulos WHERE articulos.oferta = 'si' AND articulos.stock = 'si' ORDER BY $orden LIMIT $desplazamiento, $num_filas";
	//acentos
	$con->query("SET NAMES 'utf8'");
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$id = $row["id"];
			printf ("%s %s %s %s %s %s", "</td></tr><tr><td><a href='articulos/$id.png'><img src='articulos/" . $row["id"] . ".png' width='50px' height='50px'></a>",
				"</td><td>". $row["nombre"], "</td><td>" . $row["descripcion"], "</td><td>" . $row["precio"] . "€",
				"</td><td>" . $row["oferta"],  "</td>" . "<td><input type='submit' name='" . $row["id"] . "' id='" . $row["id"] . "' value='Comprar'>");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
	echo "</td></tr></table><br>";
	//desplazamiento
	$prevpag = $desplazamiento / $num_filas;
	$currpag = $prevpag +  1;
	$nextpag = $prevpag +  2;
	if ($desplazamiento > 0) {
		$prev = $desplazamiento - $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?orden=$orden&desplazamiento=$prev";
		echo "<a href='$url'>Página $prevpag</a>&nbsp;&nbsp;&nbsp;";
	} else {
		echo "Página 1&nbsp;&nbsp;&nbsp;";
	}

	if ($total_articulos > ($desplazamiento + $num_filas)) {
		$prox = $desplazamiento + $num_filas;
		$url = $_SERVER["PHP_SELF"] . "?orden=$orden&desplazamiento=$prox";
		echo "<a href='$url'>Página $nextpag</a>";
	} else {
		echo "Página $currpag";
	}
}

//Mostramos el carrito
function mostrarCarrito(&$username) {
	echo "<br/>";
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$unidadestotal = 0;
	$preciototal = 0;
	//$username = $_SESSION['login_user'];
	if (isset ($_COOKIE["cesta_de_".$username])) {
		echo "<table border='1px,solid,black'> <tr id='titulo'> <td><b>Artículo</b></td> <td id='editar'><b>Cantidad</b></td> <td><b>Precio</b></td> </tr>";
		foreach ($_COOKIE["cesta_de_".$username] as $idarticulo => $unidades) {
			$unidadestotal = $unidadestotal + $unidades;
			//acentos
			$con->query("SET NAMES 'utf8'");
			$articulo = mysqli_query($con, "select nombre, precio from articulos WHERE id = '$idarticulo'");
			$row = mysqli_fetch_assoc($articulo);
			$precio = $row['precio'] * $unidades;
			$preciototal = $preciototal + $precio;
			printf ("%s %s %s", "<tr> <td>" . $row['nombre'], "</td> <td>" . $unidades, " &nbsp; <input type='submit' name='" 
				. $idarticulo . "' value='+'>". " <input type='submit' name='" . $idarticulo . "' value='-'>". "</td><td align='right'>" . $precio . "€</td> </tr>");
		}
		echo "<tr><td><b>Total</b></td><td><b>$unidadestotal unidades</b></td>";
		echo "<td align='right'><b>".$preciototal."€</b></td></tr></table><br/></form>";
	}else {
		echo "<h1><i>El carrito está vacío</i></h1><br/>";
	}
	echo '<br><a href="realizarcompra.php"><button>Finalizar compra</button></a><br>';
mysqli_close($con);
}

//Mostramos el carrito
function mostrarCarritoSimple(&$username) {
	echo "<br/>";
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$unidadestotal = 0;
	//$username = $_SESSION['login_user'];
	if (isset ($_COOKIE["cesta_de_".$username])) {
		echo "<table border='1px,solid,black'> <tr> <td><b>Artículo</b></td> <td><b>Cantidad</b></td> </tr>";
		foreach ($_COOKIE["cesta_de_".$username] as $idarticulo => $unidades) {
			$unidadestotal = $unidadestotal + $unidades;
			//acentos
			$con->query("SET NAMES 'utf8'");
			$articulo = mysqli_query($con, "select nombre from articulos WHERE id = '$idarticulo'");
			$row = mysqli_fetch_assoc($articulo);
			printf ("%s %s", "<tr> <td>" . $row['nombre'], "</td> <td>" . $unidades . "</td> </tr>");
		}
		echo "<tr><td colspan='2'>Número total de unidades: $unidadestotal &nbsp; </td></tr></table>";
	}else {
		echo "<h1><i>El carrito está vacío</i></h1><br/>";
	}
	echo '<br><a href="carrito.php"><button>Ver más detalles</button></a><br>';
mysqli_close($con);
}

//Agregamos una categoría
function agregarCategoria(&$nomCategoria) {
	// Comprobamos que no esté vacío el nombre
	if(!empty($nomCategoria)) {
		$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
		$query = "select nombre from categorias WHERE nombre = '$nomCategoria'";
		//acentos
		$con->query("SET NAMES 'utf8'");
		$result = mysqli_query($con, $query);
		$rows = mysqli_num_rows($result);
		// Comprobamos que no exista
		if ($rows >= 1) {
			echo "La categoría ya existe";
		} else {
			// Añadimos la categoría
			$sql = "INSERT INTO categorias (nombre) VALUES ('$nomCategoria')";
			if(!mysqli_query($con, $sql)) {
				echo "Algo ha fallado al insertar la categoría $nomCategoria en base de datos";
			} else {
				echo "Categoría $nomCategoria insertada correctamente.";	
			}
		}
	} else {
		echo "Inserte un nombre";
	}
}

//Mostramos el panel de las categorías
function mostrarPanelCategorias() {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$sql = "SELECT DISTINCT nombre FROM categorias";
	//acentos
	$con->query("SET NAMES 'utf8'");
	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$nomCategoria = $row["nombre"];

			printf ("%s %s %s", "</td></tr><tr><td><b>" . $nomCategoria . "</b>",
			"</td><td><a href='administrarcategorias.php?modcategoria=" . $nomCategoria . "'><button>Modificar</button></a>",
			"</td><td><a href='administrarcategorias.php?delcategoria=" . $nomCategoria . "'><button>Eliminar</button></a>");

		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
	echo "</td></tr></table><br>";
}

//Modificamos una categoría
function modificarCategoria(&$nomCategoria, &$nuevoNomCategoria) {
	// Comprobamos que no esté vacío el nombre
	if(!empty($nuevoNomCategoria)) {
		$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
		$query = "select nombre from categorias WHERE nombre = '$nuevoNomCategoria'";
		//acentos
		$con->query("SET NAMES 'utf8'");
		$result = mysqli_query($con, $query);
		$rows = mysqli_num_rows($result);
		// Comprobamos que no exista
		if ($rows >= 1) {
			echo "La categoría ya existe";
		} else {
			// Añadimos la categoría
			$sql = "UPDATE categorias SET nombre = '$nuevoNomCategoria' WHERE nombre = '$nomCategoria'";
			if(!mysqli_query($con, $sql)) {
				echo "Algo ha fallado al modificar la categoría $nomCategoria en base de datos";
			} else {
				echo "<b>Categoría $nomCategoria modificada a $nuevoNomCategoria correctamente.</b>";	
			}
		}
	} else {
		echo "Inserte un nombre";
	}
}

//Eliminamos una categoría
function eliminarCategoria(&$nomCategoria) {
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$query = "select nombre from categorias WHERE nombre = '$nomCategoria'";
	//acentos
	$con->query("SET NAMES 'utf8'");
	$result = mysqli_query($con, $query);
	$rows = mysqli_num_rows($result);
	// Comprobamos que exista
	if ($rows >= 1) {
		// Eliminamos la categoría
		$sql = "DELETE FROM categorias WHERE nombre = '$nomCategoria'";
		if(!mysqli_query($con, $sql)) {
			echo "Algo ha fallado al eliminar la categoría $nomCategoria de la base de datos";
		} else {
			echo "Categoría $nomCategoria eliminada correctamente.";	
		}
	} else {
		echo "La categoría no existe";
	}
}

//Mostramos los detalles de un pedido
function detallesPedido(&$numpedido) {
	$preciototal = 0;
	$cantidadtotal = 0;
	$fecha = 0;
	$estado = 0;
	$cliente = 0;
	$con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
	$sql = "SELECT lineapedido.numpedido, lineapedido.numorden, lineapedido.codarticulo, lineapedido.cantidad, lineapedido.precio, articulos.nombre, pedidos.cliente, pedidos.fecha, pedidos.estado
	FROM lineapedido, pedidos, articulos WHERE pedidos.numpedido = lineapedido.numpedido AND lineapedido.codarticulo = articulos.id AND lineapedido.numpedido = '$numpedido'
	ORDER BY lineapedido.numpedido DESC, lineapedido.numorden asc";
	//acentos
	$con->query("SET NAMES 'utf8'");

	echo "<table>";
    echo "<tr id='titulo'>";
    echo "<td><b>Pedido</b></td>";
    echo "<td><b>Orden</b></td>";
    echo "<td><b>Artículo</b></td>";
    echo "<td><b>Cantidad</b></td>";
    echo "<td><b>Precio</b></td>";

	if ($result = mysqli_query($con, $sql)) {
		while ($row = mysqli_fetch_assoc($result)) {
			$preciototal = $preciototal + $row['precio'];
			$cantidadtotal = $cantidadtotal +  $row['cantidad'];
			$fecha = $row['fecha'];
			$estado = $row['estado'];
			$cliente = $row["cliente"];
			printf ("%s %s %s %s %s", "</td></tr><tr><td>" . $row["numpedido"], "</td><td>" . $row["numorden"],
			 "</td><td>" . $row["nombre"],  "</td><td>" . $row["cantidad"], "</td><td>" . $row["precio"] . "€");
		}
		mysqli_free_result($result);
	}
	mysqli_close($con);
	echo "</td></tr></table>";
	echo "<h1>Cantidad total de artículos: $cantidadtotal</h1>";
	echo "<h1>Precio total del pedido: ". $preciototal . "€</h1>";
	echo "<h1>Cliente: $cliente</h1>";
	echo "<h1>Fecha: $fecha</h1>";
	echo "<h1>Estado actual: $estado</h1>";
}

//Damos de alta a un empleado
function altaEmpleado($cliente){
    $con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
    //acentos
    $con->query("SET NAMES 'utf8'");

    $sql = "UPDATE usuarios SET tipo = 'Empleado' WHERE username = '$cliente'";
    if (mysqli_query($con, $sql)) {
        return 'Se ha dado de alta al empleado '.$cliente.' correctamente';
    } else {
        return 'Ha habido un problema al dar de alta al empleado '.$cliente;
    }
}

//Damos de baja a un empleado
function bajaEmpleado($empleado){
    $con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
    //acentos
    $con->query("SET NAMES 'utf8'");

    $sql = "UPDATE usuarios SET tipo = 'Cliente' WHERE username = '$empleado'";
    if (mysqli_query($con, $sql)) {
        return 'Se ha dado de baja al empleado '.$empleado.' correctamente';
    } else {
        return 'Ha habido un problema al dar de baja al empleado '.$empleado;
    }
}
