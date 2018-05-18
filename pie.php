		<!-- Inicio NavAbajo -->
		<div id="navabajo">
		<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Volver</a> |
		<a href="https://github.com/dani96sp/tienda">Échale un vistazo a mi GitHub</a> |
		<a href="todo.txt">TODO</a>
		</div>
		<!-- Fin NavAbajo -->
	</div>
	<!-- Fin Cuerpo -->

	<!-- Inicio Pie -->
	<div id="pie">
		Dani &copy; 2018 tienda.dandocabezazos.es
	</div>
	<!-- Fin Pie -->
</div>
<!-- Fin Contenedor -->
</div>
<!-- Fin Borde -->
</body>
</html>
<?php
error_reporting(0);

mysqli_close($con); // Closing Connection
?>
