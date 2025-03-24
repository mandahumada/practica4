<?php
    session_start();

    error_reporting(1);

		require '../bd/conexion_bd.php';
		$obj = new BD_PDO();

		$usuario = $_SESSION["user"];

    session_destroy();
    header("Location: login.php");
        $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,nivel,fecha,hora) values('cerrar sesion','{$usuario}','aviso',Now(),CURRENT_TIME())");
        $log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,nivel,fecha,hora) values('insertar','{$usuario}','movimiento',Now(),CURRENT_TIME())");
        $fecha = date('Y-m-d H:i:s');
        $mensaje = " | el usuario " . $usuario . " ha cerrado sesion.\n";	
        $mensaje_error = $fecha . $mensaje;
        $path = '../log.txt';
        error_log($mensaje_error, 3, $path);	
    exit();
?>
