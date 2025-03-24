<style>
		.header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
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
    </header>
<?php 
$archivo = 'log.txt';

    if (file_exists($archivo)) {
        $arc = fopen($archivo, "r");
        echo "<pre>";
        while (!feof($arc)) {
            $linea = fgets($arc);
            echo htmlspecialchars($linea) . "<br>";
        }
        echo "</pre>";

        fclose($arc);
        
    } else {
        echo "El archivo no existe.";
    }

?>
