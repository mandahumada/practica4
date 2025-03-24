<?php
session_start();
$conn = new mysqli("localhost", "root", "", "aiot");
error_reporting(1);

		require '../bd/conexion_bd.php';
		$obj = new BD_PDO();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $errors = [];

        if (!preg_match("/^[a-zA-Z]{2,15}$/", $username)) {
            $errors[] = "El nombre de usuario solo puede contener letras (2-15 caracteres, sin espacios ni números).";
            $log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,nivel,fecha,hora) values('caracteres de usuario no permitidos','aviso',Now(),CURRENT_TIME())");
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | intento de ingresar un usuario con caracteres no permitidos.\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = '../log.txt';
				error_log($mensaje_error, 3, $path);	
        }

        if (strlen($password) < 6 || strlen($password) > 25 || preg_match("/\s/", $password)) {
            $errors[] = "La contraseña debe tener entre 6 y 25 caracteres y no puede contener espacios.";
            $log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,nivel,fecha,hora) values('caracteres de contrasena no permitidos','aviso',Now(),CURRENT_TIME())");
				$fecha = date('Y-m-d H:i:s');
				$mensaje = " | intento de ingresar una contrasena con caracteres no permitidos.\n";	
				$mensaje_error = $fecha . $mensaje;
				$path = '../log.txt';
				error_log($mensaje_error, 3, $path);	
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("SELECT password FROM usuarios WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($stored_password);
                $stmt->fetch();
                
                if ($password == $stored_password) {
                    $_SESSION["user"] = $username;
                    $usuario = $_SESSION["user"];
                    echo "<script>window.location.href = '../index.php';</script>";
                    $log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,usuario,nivel,fecha,hora) values('inicio de sesion','{$usuario}','aviso',Now(),CURRENT_TIME())");
                    $fecha = date('Y-m-d H:i:s');
                    $mensaje = " | " . $username . " inicio sesion.\n";	
                    $mensaje_error = $fecha . $mensaje;
                    $path = '../log.txt';
                    error_log($mensaje_error, 3, $path);	
                    exit();
                } else {
                    $errors[] = "Usuario o contraseña incorrectos.";
                    $log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,nivel,fecha,hora) values('inicio de sesion fallida','alerta',Now(),CURRENT_TIME())");
                    $fecha = date('Y-m-d H:i:s');
                    $mensaje = " | " . $username . " intento iniciar sesion.\n";	
                    $mensaje_error = $fecha . $mensaje;
                    $path = '../log.txt';
                    error_log($mensaje_error, 3, $path);	
                }
            } else {
                $errors[] = "Usuario o contraseña incorrectos.";
                $log_insertar = $obj->Ejecutar_Instruccion("insert into logs(accion,nivel,fecha,hora) values('inicio de sesion fallida','alerta',Now(),CURRENT_TIME())");
                    $fecha = date('Y-m-d H:i:s');
                    $mensaje = " | " . $username . " intento iniciar sesion.\n";	
                    $mensaje_error = $fecha . $mensaje;
                    $path = '../log.txt';
                    error_log($mensaje_error, 3, $path);	
            }
            $stmt->close();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color:rgb(237, 228, 246); 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 20px;
            width: 350px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color:rgb(215, 138, 227); 
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color:rgb(177, 94, 190); 
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($errors)) { 
            foreach ($errors as $error) { 
                echo "<p class='error-message'>$error</p>"; 
            } 
        } ?>
        <form method="POST" action="">
            <label>Usuario:</label>
            <input type="text" name="username" required>
            
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            
            <button type="submit">Ingresar</button>
        </form>
    </div>

</body>
</html>

