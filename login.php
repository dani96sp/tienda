<?php
if (empty($error)){
	echo '<h2>Introduce tus datos</h2>';
}else{
	echo '<h2>DATOS INCORRECTOS</h2>';
}
?>


<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<label>Usuario :</label>
<input name="username" placeholder="usuario" type="text">
<label>Contraseña :</label>
<input name="password" placeholder="**********" type="password">
<input name="submit" type="submit" value=" login "><br>
<span><h3><?php echo $error; ?></h></span>
</form>

		</div>

		<h2 class="titlat">Registro</h2>
		<div id="registro" class="cuerpolateral">
			<a href="usuarionuevo.php">Registrese con nosotros</a> y obtenga muchas ventajas.
		</div>

