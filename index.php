<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar en WordPress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        #message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        #message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <h1>Crear una entrada en WordPress</h1>
    <label for="title">Título:</label>
    <input type="text" id="title" placeholder="Título de la entrada"><br>
    <label for="content">Contenido:</label>
    <textarea id="content" placeholder="Contenido de la entrada"></textarea><br>
    <button id="publicar">Publicar</button>

    <div id="message"></div>

    <script>
        document.getElementById('publicar').addEventListener('click', function () {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();

            // Validación de campos
            if (!title || !content) {
                showMessage('Por favor, rellena todos los campos.', 'error');
                return;
            }

            const postData = { title, content };

            fetch('http://tu-servidor/controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postData)
            })
            .then(response => response.json())
            .then(data => {
                if (response.ok) {
                    showMessage('Publicación realizada con éxito.', 'success');
                } else {
                    const errorMessage = data.error || 'Error desconocido al publicar.';
                    showMessage(`Error: ${errorMessage}`, 'error');
                }
                console.log('Respuesta:', data);
            })
            .catch(error => {
                console.error('Error al publicar:', error);
                showMessage('Error al conectar con el servidor.', 'error');
            });
        });

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = '';
            messageDiv.classList.add(type === 'success' ? 'success' : 'error');
            messageDiv.style.display = 'block';
        }
    </script>
</body>
</html>
