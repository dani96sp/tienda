		</div>
<?php
    $con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);

    $sql = "select nombre from usuarios where username='$_SESSION[login_user]'";
    $result = mysqli_query($con, $sql);
    $nombre = mysql_result($result, 0);

    //$query = mysql_query("select username from usuarios where username='$_SESSION[login_user]'", $connection);
    $username = $_SESSION['login_user'];
    $sql = "select telefono from usuarios where username='$_SESSION[login_user]'";
    $result = mysqli_query($con, $sql);
    $telefono = mysql_result($result, 0);
    $sql = "select email from usuarios where username='$_SESSION[login_user]'";
    $result = mysqli_query($con, $sql);
    $email = mysql_result($result, 0);
    $sql = "select tipo from usuarios where username='$_SESSION[login_user]'";
    $result = mysqli_query($con, $sql);
    $tipo = mysql_result($result, 0);

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

