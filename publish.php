<?php

// Configuración de la API de WordPress
$wordpress_url = 'http://wordpress.yisus.lan/wp-json/wp/v2/posts/';
$username = 'admin'; 
$api_key = 'G7pS 2dKf jJrT 2Qaq Jb88 chYn=';

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'getWeather') {
        // Obtener datos del tiempo desde el-tiempo.net
        $weather_api_url = 'https://www.el-tiempo.net/api/json/v2/provincias/30';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $weather_api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code === 200) {
            $weather_data = json_decode($response, true);

            // Extraer datos necesarios
            $nameProvince = $weather_data['ciudades'][0]['nameProvince'] ?? 'N/A';
            $todayDescription = $weather_data['today']['p'] ?? 'N/A';
            $metaDescription = $weather_data['metadescripcion'] ?? 'N/A';
            $firstCity = $weather_data['ciudades'][0] ?? null;
            $cityName = $firstCity['name'] ?? 'N/A';
            $temperatureMax = $firstCity['temperatures']['max'] ?? 'N/A';
            $temperatureMin = $firstCity['temperatures']['min'] ?? 'N/A';

            echo json_encode([
               'nameProvince' => $nameProvince,
            'todayDescription' => $todayDescription,
            'metaDescription' => $metaDescription,
            'cityName' => $cityName,
            'temperatureMax' => $temperatureMax,
            'temperatureMin' => $temperatureMin
            ]);
        } else {
            http_response_code($http_code);
            echo json_encode(['error' => 'No se pudo obtener el tiempo']);
        }
        exit;
    }

    if ($_GET['action'] === 'publishWeather') {
        // Publicar datos en WordPress
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['title']) || empty($input['content'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
            exit;
        }

        $title = $input['title'];
        $content = $input['content'];

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
            echo json_encode(['success' => true, 'message' => 'Publicación creada con éxito.']);
        } else {
            http_response_code($http_code);
            echo json_encode(['success' => false, 'message' => 'Error al crear la publicación.']);
        }
        exit;
    }
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
