<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opiniones Turísticas API</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            background-image: url('https://espanol.cgtn.com/news/2025-02-16/1890935938038693889/1739669130099.jpg');
            background-size: cover;
            background-attachment: fixed;
            color: #333;
        }
        #app { max-width: 800px; margin: 2em auto; background: rgba(255, 255, 255, 0.95); padding: 2em; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1, h2 { color: #2c3e50; }
        form { display: grid; grid-gap: 10px; margin-bottom: 2em; }
        input, select, textarea, button { padding: 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 1em; }
        button { background-color: #3498db; color: white; cursor: pointer; border: none; }
        button:hover { background-color: #2980b9; }
        #opinions-list { list-style: none; padding: 0; }
        #opinions-list li { background: #ecf0f1; margin-bottom: 10px; padding: 15px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 5px 10px; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px; }
        .read-btn { background-color: #27ae60; }
        .read-btn:hover { background-color: #229954; }
        .delete-btn { background-color: #e74c3c; }
        .delete-btn:hover { background-color: #c0392b; }
        /* Estilos para el botón de exportación */
        .export-btn { 
            display: inline-block; 
            text-decoration: none; 
            margin-bottom: 1em; 
            background-color: #16a085; 
        }
        .export-btn:hover { 
            background-color: #117a65; 
        }
        /* Estilos para el Modal */
        #modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px; border-radius: 8px; }
        .close-btn { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<div id="app">
    <h1>Gestor de Opiniones Turísticas</h1>

    <h2>Añadir Nueva Opinión</h2>
    <form id="add-opinion-form">
        <input type="text" id="title" placeholder="Título" required>
        <textarea id="review" placeholder="Reseña" required></textarea>
        <input type="number" id="polarity" placeholder="Polaridad (1-5)" min="1" max="5" required>
        <input type="text" id="town" placeholder="Pueblo Mágico" required>
        <input type="text" id="region" placeholder="Región" required>
        <select id="type" required>
            <option value="Attractive">Attractive</option>
            <option value="Hotel">Hotel</option>
            <option value="Restaurant">Restaurant</option>
        </select>
        <input type="text" id="usuario" placeholder="Nombre de usuario" required>
        <button type="submit">Guardar Opinión</button>
    </form>

    <h2>Opiniones Existentes</h2>
    <a href="/api/opinions/export" class="btn export-btn">Descargar todo como CSV</a>
    <ul id="opinions-list">
        </ul>
</div>

<div id="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <div id="modal-body"></div>
    </div>
</div>

<script>
    const apiUrl = '/api/opinions';
    const opinionsList = document.getElementById('opinions-list');
    const addForm = document.getElementById('add-opinion-form');
    const modal = document.getElementById('modal');
    const modalBody = document.getElementById('modal-body');
    const closeBtn = document.querySelector('.close-btn');

    // --- FUNCIÓN PARA CARGAR LAS OPINIONES ---
    async function fetchOpinions() {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) throw new Error('Error al cargar datos');
            const opinions = await response.json();

            opinionsList.innerHTML = ''; // Limpiar la lista
            opinions.forEach(opinion => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <span>
                        <strong>${opinion.title}</strong> (${opinion.town})
                    </span>
                    <div>
                        <button class="btn read-btn" data-id="${opinion.id}">Leer</button>
                        <button class="btn delete-btn" data-id="${opinion.id}">Eliminar</button>
                    </div>
                `;
                opinionsList.appendChild(li);
            });
        } catch (error) {
            console.error('Error:', error);
            opinionsList.innerHTML = '<li>Error al cargar las opiniones.</li>';
        }
    }

    // --- FUNCIÓN PARA LEER UNA OPINIÓN (NUEVA) ---
    async function readOpinion(id) {
        try {
            const response = await fetch(`${apiUrl}/${id}`);
            if (!response.ok) throw new Error('Opinión no encontrada');
            const opinion = await response.json();

            modalBody.innerHTML = `
                <h3>${opinion.title}</h3>
                <p><strong>Lugar:</strong> ${opinion.town}, ${opinion.region} (${opinion.type})</p>
                <p><strong>Usuario:</strong> ${opinion.usuario}</p>
                <p><strong>Polaridad:</strong> ${opinion.polarity} / 5</p>
                <hr>
                <p>${opinion.review}</p>
            `;
            modal.style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
            alert(error.message);
        }
    }

    // --- EVENTO PARA ENVIAR EL FORMULARIO ---
    addForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const newOpinion = {
            title: document.getElementById('title').value,
            review: document.getElementById('review').value,
            polarity: parseInt(document.getElementById('polarity').value),
            town: document.getElementById('town').value,
            region: document.getElementById('region').value,
            type: document.getElementById('type').value,
            usuario: document.getElementById('usuario').value,
        };
        try {
            const response = await fetch(apiUrl, {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(newOpinion)
            });
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error('Error al guardar: ' + JSON.stringify(errorData.errors));
            }
            addForm.reset();
            fetchOpinions();
        } catch (error) {
            console.error('Error:', error);
            alert(error.message);
        }
    });

    // --- EVENTO PARA CLICS EN LA LISTA (LEER Y BORRAR) ---
    opinionsList.addEventListener('click', async (e) => {
        if (e.target) {
            if (e.target.classList.contains('delete-btn')) {
                const opinionId = e.target.dataset.id;
                if (!confirm('¿Estás seguro?')) return;
                try {
                    const response = await fetch(`${apiUrl}/${opinionId}`, { method: 'DELETE' });
                    if (!response.ok) throw new Error('No se pudo eliminar.');
                    fetchOpinions();

                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message);
                }
            }
            if (e.target.classList.contains('read-btn')) {
                const opinionId = e.target.dataset.id;
                readOpinion(opinionId);
            }
        }
    });

    // --- CERRAR EL MODAL ---
    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

    // --- CARGA INICIAL ---
    document.addEventListener('DOMContentLoaded', fetchOpinions);
</script>

</body>
</html>