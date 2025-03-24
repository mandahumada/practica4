<?php session_start();?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<script>
		function Eliminar(id) {
			if(confirm("¿ Estas seguro de eliminar?"))
			{
				window.location = "index.php?ideliminar="+id;
			}			
		}

		function Modificar(id) {
			window.location = "index.php?idmodificar="+id;						
		}

	</script>
	<style>
		.header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            height: 40px;
            margin-right: 50px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            font-size: 16px;
            padding: 8px 15px;
            transition: color 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .menu-links {
            display: flex;
            gap: 50px;
        }

        .menu-links a {
            text-decoration: none;
            font-size: 16px;
            color: rgb(109, 108, 108);
        }

        .btn-red {
            background-color: #de0404;
            color: white;
            padding: 8px 12px;
            border-radius: 15px;
        }
        
        .btn-gray {
            background-color: gray;
            color: white;
            padding: 8px 12px;
            border-radius: 15px;
        }

        .btn-white {
            background-color: #f8f9fa;
            color: white;
            padding: 8px 10px;
            border-radius: 20px;
            border: 1px solid gray;
        }

        .nav-links a:hover, .menu-links a:hover {
            opacity: 0.8;
        }
	</style>
</head>
<body>
<header class="header">
        <div class="logo-container">
            <nav class="menu-links">
                <a href="index.php">Inicio</a>
                <a href="ver_logs.php">Archivo Log</a>
                <a href="ver_bd.php">Base de Datos Logs</a>
            </nav>
        </div>
        <nav class="nav-links">
            <?php 
			error_reporting(1);
			$usuario = $_SESSION["user"];
			if ($usuario == null) {
				echo "<a href='login/login.php' class='btn-red'>Iniciar sesion</a>";
			} else if ($usuario != null) {
				echo "<a href='login/logout.php' class='btn-red'>Cerrar Sesion</a>";
			}
			
			 ?>
            <!-- <a href="#" class="btn-gray">Registrarse</a> -->
            <div class="menu-icon" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>
            
        </nav>
    </header>

	<?php  
	
	error_reporting(1);

		require 'bd/conexion_bd.php';
		$obj = new BD_PDO();

		$datos_inicio = $obj ->Ejecutar_Instruccion("SELECT COUNT(*) total FROM dodo");
		$usuario = $_SESSION["user"];

		if ($usuario == null && basename($_SERVER['PHP_SELF']) == "index.php") {
			// echo "<a href='login/login.php'>Iniciar sesion</a>";
			$log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,nivel,fecha,hora) values('inicio sesion','sin usuario','aviso',Now(),CURRENT_TIME())");
			$log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,nivel,fecha,hora) values('insertar','{$usuario}','movimiento',Now(),CURRENT_TIME())");
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | uso de pagina sin iniciar sesion.\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = 'log.txt';
				error_log($mensaje_error, 3, $path);
		} else if ($usuario != null) {
			// echo "<a href='login/logout.php'>Cerrar sesion</a>";

		}
		
		if (isset($_POST['btnregistrar']))  {		
			$fecha = $_POST['txtfecha'];
			$hora = $_POST['txthora'];
			$dispositivo = $_POST['txtdispositivo'];
			$accion = $obj->Ejecutar_Instruccion("insert into dodo(fecha,hora,dispositivo) values(Now(),CURRENT_TIME(),'{$dispositivo}')");
			$datos_insertados = $obj ->Ejecutar_Instruccion("SELECT COUNT(*) total FROM dodo");
			if ($datos_inicio != $datos_insertados ) {
				$log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,nivel,fecha,hora) values('insertar','{$usuario}','movimiento',Now(),CURRENT_TIME())");
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | el usuario " . $usuario . " ha realizado un nuevo registro.\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = 'log.txt';
				error_log($mensaje_error, 3, $path);				 	 
			}
		}
		
		if (isset($_POST['btnactualizar'])) {			
			$id = $_POST['txtid'];	
			$fecha = $_POST['txtfecha'];
			$hora = $_POST['txthora'];
			$dispositivo = $_POST['txtdispositivo'];
			$datos_iniciales = $obj->Ejecutar_Instruccion("select * from dodo where id = '{$id}'");
			
			$accion_eliminar = $obj->Ejecutar_Instruccion("update dodo set fecha='{$fecha}',hora='{$hora}',dispositivo='{$dispositivo}' where id='{$id}'");		 
			$datos_actualizados = $obj->Ejecutar_Instruccion("select * from dodo where id = '{$id}'");
			if ($datos_iniciales == $datos_actualizados) {
				$log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,id_afectado,nivel,fecha,hora) values('intento de modificar','{$usuario}','{$id}','aviso',Now(),CURRENT_TIME())");
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | el usuario " . $usuario . " intento actualizar el registro " . $id . ".\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = 'log.txt';
				error_log($mensaje_error, 3, $path);
			} else {
				$log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,id_afectado,nivel,fecha,hora) values('modificacion','{$usuario}','{$id}','movimiento',Now(),CURRENT_TIME())");
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | el usuario " . $usuario . " ha actualizado el registro " . $id . " correctamente.\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = 'log.txt';
				error_log($mensaje_error, 3, $path);
			}
		}

		if (isset($_GET['ideliminar'])) {
			$id = $_GET['ideliminar'];
			$obj->Ejecutar_Instruccion("delete from dodo where id = '{$id}'");
			$log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,id_afectado,nivel,fecha,hora) values('eliminar','{$usuario}','{$id}','movimiento',Now(),CURRENT_TIME())");
			$datos_actuales = $obj ->Ejecutar_Instruccion("SELECT COUNT(*) total FROM dodo");
			if ($datos_inicio != $datos_actuales) {
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | el usuario " . $usuario . " ha eliminado el registro " . $id . ".\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = 'log.txt';
				error_log($mensaje_error, 3, $path);
			} else {
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | el usuario " . $usuario . " ha intentado eliminado el registro " . $id . ".\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = 'log.txt';
				error_log($mensaje_error, 3, $path);
			}
		}

		if (isset($_GET['idmodificar'])) {
			$id = $_GET['idmodificar'];
			$result_modificar = $obj->Ejecutar_Instruccion("select * from dodo where id = '{$id}'");
		}

		$buscar = $_POST['txtbuscarque'];
		$result = $obj->Ejecutar_Instruccion("Select * from dodo where dispositivo like '%{$buscar}%'");
		$buscado = $result[0][0];
		
		if (isset($_POST['btnbuscar'])) {
			$log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,id_afectado,nivel,fecha,hora) values('buscar','{$usuario}','{$buscado}','movimiento',Now(),CURRENT_TIME())");
			$fecha = date('Y-m-d H:i:s');
			$mensaje = " | el usuario " . $usuario . " ha buscado un registro.\n";	
			$mensaje_error = $fecha . $mensaje;
			$path = 'log.txt';
			error_log($mensaje_error, 3, $path);
		}

	?>

	<div class="container">
		<form action="index.php" method="post">
			<label for="">id</label>
			<input type="text" id="txtid" name="txtid" value="<?php echo $result_modificar[0][0]; ?>">
			<label for="">fecha</label>
			<input type="date" id="txtfecha" name="txtfecha" value="<?php echo $result_modificar[0]['fecha']; ?>">
			<label for="">hora</label>
			<input type="text" id="txthora" name="txthora" value="<?php echo $result_modificar[0]['hora']; ?>">
			<label for="">dispositivo</label>
			<input type="text" id="txtdispositivo" name="txtdispositivo" value="<?php echo $result_modificar[0][3]; ?>">

			<?php 			
			if (isset($_GET['idmodificar'])) {
				echo '<input type="submit" id="btnactualizar" name="btnactualizar" value="Actualizar">';
				} else {
					echo '<input type="submit" id="btnregistrar" name="btnregistrar" value="Registrar">';
				}
			?>	

		</form>
	</div>
	<br>
	<form action="index.php" method="post">
		<label for="">Buscar</label>
		<input type="text" id="txtbuscarque" name="txtbuscarque">		
		<input type="submit" id="btnbuscar" name="btnbuscar" value="Buscar">
		<table border="1">
			<tr>
				<td>id</td>
				<td>fecha</td>
				<td>hora</td>
				<td>dispositivo</td>
				<td>Accion</td>
			</tr>
			<?php foreach ($result as $renglon) { ?>
			<tr>
				<td><?php echo $renglon[0]; ?></td>
				<td><?php echo $renglon[1]; ?></td>
				<td><?php echo $renglon[2]; ?></td>
				<td><?php echo $renglon[3]; ?></td>
				<td><input type="button" id="btnmodificar" name="btnmodificar" value="Modificar" onClick="Modificar('<?php echo $renglon['id']; ?>')"></td>
				<td><input type="button" id="btneliminar" name="btneliminar" value="Eliminar" onClick="Eliminar('<?php echo $renglon['id']; ?>')"></td>
			</tr>
			<?php } ?>
		</table>
	</form>
</body>
</html>