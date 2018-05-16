		</div>
<?php
    $con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);

    $username = $_SESSION['login_user'];

    $sql = "select nombre, telefono, email, tipo from usuarios where username='$_SESSION[login_user]'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $nombre = $row['nombre'];
    $telefono = $row['telefono'];
    $email = $row['email'];
    $tipo = $row['tipo'];

?>

<h2 class="titlat">Bienvenido <b><?php echo $nombre ?></b></h2>
<div id="registro" class="cuerpolateral">
    <div id="otras" class="cuerpolateral">
        <h3>
            <ul>
                <li><a href="perfil.php">Mi perfil</a></li>
                <li><a href="compras.php">Mis compras</a></li>
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

