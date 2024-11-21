<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar en WordPress</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Crear Publicación en WordPress</h1>
        <form action="publish.php" method="POST" id="postForm">
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" placeholder="Escribe el título aquí" required><br><br>
            
            <label for="content">Contenido:</label><br>
            <textarea id="content" name="content" rows="10" placeholder="Escribe el contenido aquí" required></textarea><br><br>
            
            <button type="submit">Publicar</button>
        </form>
        <br>
        <!-- Botón adicional con el texto "Tiempo" -->
        <button type="button" id="getWeatherButton">Tiempo</button>
    </div>

    <!-- Modal para mensaje emergente -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <p id="modalMessage"></p>
            <button onclick="closeModal()">Cerrar</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

