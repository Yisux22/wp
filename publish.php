<?php

// Configuración de la API de WordPress
$wordpress_url = 'http://wordpress.yisus.lan/wp-json/wp/v2/posts/';
$username = 'admin'; 
$api_key = 'G7pS 2dKf jJrT 2Qaq Jb88 chYn=';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['title']) || empty($_POST['content'])) {
        http_response_code(400);
        echo "Por favor, completa todos los campos.";
        exit;
    }

    $title = $_POST['title'];
    $content = $_POST['content'];

    $data = [
        'title' => $title,
        'content' => $content,
        'status' => 'publish'
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $wordpress_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("$username:$api_key")
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_code === 201) {
        echo "¡Publicación creada con éxito!";
    } else {
        http_response_code($http_code);
        echo "Error al crear la publicación: " . $response;
    }
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
