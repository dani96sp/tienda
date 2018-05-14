		</div>
<?php
		// Selecting Database
		$db = mysql_select_db(DATABASE, $connection);
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("select nombre from usuarios where username='$_SESSION[login_user]'", $connection);
		$nombre = mysql_result($query, 0);

		//$query = mysql_query("select username from usuarios where username='$_SESSION[login_user]'", $connection);
		$username = $_SESSION['login_user'];
		$query = mysql_query("select telefono from usuarios where username='$_SESSION[login_user]'", $connection);
		$telefono = mysql_result($query, 0);
		$query = mysql_query("select email from usuarios where username='$_SESSION[login_user]'", $connection);
		$email = mysql_result($query, 0);
		$query = mysql_query("select tipo from usuarios where username='$_SESSION[login_user]'", $connection);
		$tipo = mysql_result($query, 0);


?>

<h2 class="titlat">Bienvenido <b><?php echo $nombre ?></b></h2>
<div id="registro" class="cuerpolateral">
			<div id="otras" class="cuerpolateral">
			<h3>
			<ul>
			<li><a href="perfil.php">Mi perfil</a>
			<li><a href="compras.php">Mis compras</a>
			<?php
				if($tipo == 'Empleado') {
					include("empleado.php");
				}
				if($tipo == 'SuperUsuario') {
					include("superusuario.php");
				}
			?>
			</ul>
			</h3>
		</div>
	<a href="logout.php"><button>Cerrar sesión</button></a>
</div>

