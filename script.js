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
