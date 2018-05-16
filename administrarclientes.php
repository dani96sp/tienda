<?php
$titulo_pagina = "Administrar Clientes";
$descripcion = "administrar clientes";
$keywords = "administrar, clientes, palabras clave, keywords";

//Incluimos las funciones
include("funciones.php");
include("seguridad.php");
//Se incluye la cabecera y comienza el cuerpo de la página a continuación
include("cabecera.php");

// Comprobamos si se ha hecho submit
if(isset($_REQUEST['enviar'])) {
    $submit = true;
} else $submit = false;

// Comprobamos el parámetro que se pasa
if (isset($_REQUEST['modcliente'])) {
    $option = 'mod';
    $cliente = $_REQUEST['modcliente'];
} else if (isset($_REQUEST['altaempleado'])) {
    $option = 'alta';
    $cliente = $_REQUEST['altaempleado'];
}

switch ($option) {
    case 'mod':
    // Mostramos el contenido de modificar un cliente
        $con = mysqli_connect(HOSTNAME, USER_DB, PASSWORD_DB, DATABASE);
        //acentos
        $con->query("SET NAMES 'utf8'");

        $cNombre = "correcto";
        $cTelefono = "correcto";
        $cEmail = "correcto";

        $sql = "SELECT * FROM usuarios WHERE username = '$cliente'";
        if ($result = mysqli_query($con, $sql)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $nombre = $row["nombre"];
                $telefono = $row["telefono"];
                $email = $row["email"];
            }
            mysqli_free_result($result);
        }


        if($submit){
            $nombre = $_POST["nombre"];
            $telefono = $_POST["telefono"];
            $email = $_POST["email"];

            //validar campos
            $er_nombre = "/^([A-ZÁÉÍÓÚ]{1}[a-zñáéíóú]+[\s]*){2,}$/";
            $er_telefono = "/^\d{9}$/";
            $er_email = "/^(.+\@.+\..+)$/";

            if (!preg_match($er_nombre, $nombre)) {
                $nombre = "";
            }
            if (!preg_match($er_telefono, $telefono)) {
                $telefono = "";
            }
            if (!preg_match($er_email, $email)) {
                $email = "";
            }

            if(empty($nombre)|empty($telefono)|empty($email)) {

                if(empty($nombre)) {
                    $cNombre = "incorrecto";
                } else {
                    $cNombre = "correcto";
                }

                if(empty($telefono)) {
                    $cTelefono = "incorrecto";
                } else {
                    $cTelefono = "correcto";
                }

                if(empty($email)) {
                    $cEmail = "incorrecto";
                } else {
                    $cEmail = "correcto";
                }

                echo "<h1>Algunos datos son incorrectos</h1>";

            } else { // si todo es correcto modificamos y le indicamos al usuario que todo ha ido correctamente
                $sql = "UPDATE usuarios SET nombre = '$nombre', telefono = '$telefono', email = '$email' WHERE username = '$cliente'";
                mysqli_query($con, $sql);
                echo "<h1><i>Cambios guardados</i></h1>";
            }
        }

        //Comprobamos si el usuario editando el cliente tiene permisos de superusuario
        //Para poder dar de alta a clientes como empleados.
        $sql = "select tipo from usuarios where username='$_SESSION[login_user]'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_row($result);
        $tipo = $row[0];
?>
        <h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>
        <fieldset>
            <legend>Modificar datos</legend>
            <form name="formulario" id="formulario" action="administrarclientes.php?modcliente=<?php echo $cliente ?>" method="post">
                <input type="hidden" name="cliente" value="<?php echo $cliente ?>">
                <label for="nombre" id="<?php echo $cNombre ?>">Nombre y apellidos:</label>
                <input type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $nombre ?>"/><br/>
                <label for="telefono" id="<?php echo $cTelefono ?>">Teléfono:</label>
                <input name="telefono" type="text" id="telefono" maxlength="9" value="<?php echo $telefono ?>"/><br/>
                <label for="email" id="<?php echo $cEmail ?>">E-mail:</label>
                <input name="email" type="text" id="email" maxlength="40" value="<?php echo $email ?>" /><br/>
                <input type="submit" name="enviar" id="enviar" value="Guardar datos"/>
                <input type="reset" name="limpiar" id="button" value="Restablecer datos" ><br/>
            </form>
<?php
            if ($tipo == 'SuperUsuario') {
                //TODO
?>
                <a href="administrarclientes.php?altaempleado=<?php echo $cliente ?>"><button>Dar de alta como empleado</button></a>
<?php
            }
?>
        </fieldset>
        <br/><br/>
<?php
        break;
    case 'alta':
        echo '<h1>'. parametro_plantilla("titulo_pagina") .'</h1>';
        echo altaEmpleado($cliente);
        echo "<br/><br/>";
        //No hacemos break para que muestre el default también
    default:
    // Se muestra siempre que no se le haya dado a una de esas opciones
?>
        <h1><?php echo parametro_plantilla("titulo_pagina") ?></h1>
        <table>
            <tr id='titulo'>
                <td><b>Nombre de Usuario</b></td>
                <td><b>Nombre completo</b></td>
                <td><b>Teléfono</b></td>
                <td><b>E-mail</b></td>
                <td id='editar'><b>Editar</b></td>
<?php
                //Llamamos a la función mostrarClientes()
                //para que monte la tabla con los clientes
                mostrarClientes();
}

//Por último añadimos el pie
include("pie.php");
?>