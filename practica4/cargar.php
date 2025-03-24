<?php
$archivo = "log.txt"; // Ruta del archivo
$contenido = file_get_contents($archivo);
$data = json_encode(["contenido" => $contenido]);

// URL de Elasticsearch
$url = "http://localhost:9200/archivos/_doc/1";

// Credenciales (usuario y contraseña)
$username = "elastic"; // Usuario por defecto
$password = "NsN61CWVyyKNl5Ljy8RX"; // Reemplaza con tu contraseña

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

$response = curl_exec($ch);
curl_close($ch);
echo "Archivo enviado a Elasticsearch: " . $response;
?>