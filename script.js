document.getElementById('postForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevenir envío del formulario por defecto

    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;

    if (!title || !content) {
        showModal('Por favor, completa todos los campos.');
        return;
    }

    const formData = new FormData(this);

    try {
        const response = await fetch('publish.php', {
            method: 'POST',
            body: formData
        });

        const textResponse = await response.text();

        if (response.ok) {
            showModal('¡Publicación creada con éxito!');
            document.getElementById('title').value = ''; // Limpiar campos
            document.getElementById('content').value = '';
        } else {
            showModal('Error al crear la publicación: ' + textResponse);
        }
    } catch (error) {
        console.error('Error:', error);
        showModal('Ocurrió un error inesperado.');
    }
});
document.getElementById('getWeatherButton').addEventListener('click', async () => {
    try {
        // Obtener los datos de la API de el-tiempo.net
        const response = await fetch('publish.php?action=getWeather');
        const weatherData = await response.json();

        if (weatherData.error) {
            console.error('Error al consultar el tiempo:', weatherData.error);
        } else {
            // Construir el contenido para la publicación en WordPress
            const content = `
            <h2>${weatherData.nameProvince}</h2>
            <p><strong>Pronóstico de hoy:</strong> ${weatherData.todayDescription}</p>
            <p><strong>Descripción adicional:</strong> ${weatherData.metaDescription}</p>
            <p><strong>Temperatura máxima:</strong> ${weatherData.temperatureMax}°C</p>
            <p><strong>Temperatura mínima:</strong> ${weatherData.temperatureMin}°C</p>
        `;

            // Enviar los datos al servidor para publicarlos en WordPress
            const postResponse = await fetch('publish.php?action=publishWeather', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    title: 'TiempoMurcia',
                    content: content
                })
            });

            const postResult = await postResponse.json();

            if (postResult.success) {
                console.log('¡Publicación creada con éxito en WordPress!');
                alert('¡Publicación creada con éxito en WordPress!');
            } else {
                console.error('Error al publicar en WordPress:', postResult.message);
                alert('Error al publicar en WordPress: ' + postResult.message);
            }
        }
    } catch (error) {
        console.error('Error general:', error);
        alert('Ocurrió un error inesperado.');
    }
});



// Función para mostrar el modal
function showModal(message) {
    const modal = document.getElementById('successModal');
    const modalMessage = document.getElementById('modalMessage');
    modalMessage.textContent = message;
    modal.style.display = 'flex';
}

// Función para cerrar el modal
function closeModal() {
    const modal = document.getElementById('successModal');
    modal.style.display = 'none';
}

// Función para mostrar el modal
function showModal(message) {
    const modal = document.getElementById('successModal');
    const modalMessage = document.getElementById('modalMessage');
    modalMessage.textContent = message;
    modal.style.display = 'flex';
}

// Función para cerrar el modal
function closeModal() {
    const modal = document.getElementById('successModal');
    modal.style.display = 'none';
}
