<?php
function return_response($status, $statusMessage, $data) {
    header("HTTP/1.1 $status $statusMessage");
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($data);
}

// Obtener datos del cuerpo de la solicitud
$bodyRequest = file_get_contents("php://input");
if ($bodyRequest === false) {
    return_response(500, "Error", ["error" => "No se pudo leer el cuerpo de la solicitud."]);
    exit;
}

$requestData = json_decode($bodyRequest, true);
if (!$requestData || !isset($requestData['title']) || !isset($requestData['content'])) {
    return_response(400, "Bad Request", ["error" => "Datos inválidos o incompletos. Se requiere 'title' y 'content'."]);
    exit;
}

// Preparar los datos para enviar a la API de WordPress
$postData = json_encode([
    'title' => $requestData['title'],
    'content' => $requestData['content'],
    'status' => 'publish'
]);

if ($postData === false) {
    return_response(500, "Error", ["error" => "Error al codificar los datos en formato JSON."]);
    exit;
}

// Configurar la solicitud cURL
$ch = curl_init('http://wordpress.yisus.lan/wp-json/wp/v2/posts/');
if (!$ch) {
    return_response(500, "Error", ["error" => "Error al inicializar cURL."]);
    exit;
}

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic YWRtaW46RzdwUyAyZEtmIGpKclQgMlFhcSBKYjg4IGNoWW4='
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

// Habilitar logs de cURL para depuración
$curl_log = fopen('curl.log', 'w+');
if ($curl_log === false) {
    return_response(500, "Error", ["error" => "No se pudo crear el archivo de log para cURL."]);
    exit;
}
curl_setopt($ch, CURLOPT_STDERR, $curl_log);

// Ejecutar la solicitud cURL
$response = curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    $error_message = curl_error($ch);
    curl_close($ch);
    fclose($curl_log);
    return_response(500, "Error", ["error" => "Error de cURL: $error_message"]);
    exit;
}

// Validar la respuesta de WordPress
if ($responseCode >= 200 && $responseCode < 300) {
    // Éxito
    curl_close($ch);
    fclose($curl_log);
    return_response($responseCode, "Success", ["response" => json_decode($response, true)]);
} else {
    // Error
    $raw_response = $response ?: "No se recibió una respuesta de WordPress.";
    curl_close($ch);
    fclose($curl_log);
    return_response($responseCode, "Error", [
        "error" => "La solicitud a WordPress falló con el código HTTP $responseCode.",
        "raw_response" => $raw_response
    ]);
}
?>

