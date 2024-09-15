// Función para obtener los datos del menú desde el servidor
async function fetchData(mealType) {
    try {
        const response = await fetch('../../php_basesDatos/MenuCRUD.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ mealType, action: 'read' })
        });
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
        Swal.fire({ title: 'Error', text: 'No se pudieron obtener los datos. Por favor, intente de nuevo más tarde.', icon: 'error' });
    }
}

// Función para mostrar los datos del menú en una tabla dentro de un SweetAlert2
function showData(title, data) {
    const table = `
        <table class="table">
            <thead>
                <tr>
                    <th>Tipo de Menú</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                ${data.map(item => `
                    <tr>
                        <td>${item.tipomenu || 'No especificado'}</td>
                        <td>${item.fecha}</td>
                        <td>${item.descripcion}</td>
                        <td>
                            <button class="btn btn-primary" onclick="editItem(${item.idmenu})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" onclick="deleteItem(${item.idmenu})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    Swal.fire({ title, html: table, width: '600px', showConfirmButton: false });
}

// Función para crear un nuevo menú
async function createNewMenu() {
    const { value: mealType } = await Swal.fire({
        title: 'Seleccionar Tipo de Menú',
        input: 'select',
        inputOptions: { desayuno: 'Desayuno', almuerzo: 'Almuerzo', refrigerio: 'Refrigerio' },
        inputPlaceholder: 'Seleccionar',
        showCancelButton: true,
        inputValidator: value => !value && 'Debe seleccionar un tipo de menú!'
    });
    if (mealType) {
        const { value: description } = await Swal.fire({
            title: 'Descripción del Nuevo Menú',
            input: 'text',
            inputLabel: 'Descripción',
            showCancelButton: true,
            inputValidator: value => !value && 'La descripción es requerida!'
        });
        const { value: date } = await Swal.fire({
            title: 'Fecha del Menú',
            input: 'date',
            inputLabel: 'Fecha',
            showCancelButton: true,
            inputValidator: value => !value && 'La fecha es requerida!'
        });

        if (description && date) {
            const payload = { mealType, descripcion: description, fecha: date, action: 'create' };
            if (await sendData(payload)) {
                const data = await fetchData(mealType);
                showData(`Menú de ${mealType}`, data);
            }
        }
    }
}

// Función para eliminar un ítem del menú
async function deleteItem(id) {
    const result = await Swal.fire({
        title: 'Confirmar Eliminación',
        text: "¿Estás seguro de que quieres eliminar este ítem del menú?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (result.isConfirmed) {
        const payload = { idmenu: id, action: 'delete' };
        if (await sendData(payload)) {
            const mealType = document.querySelector('.feature-box.active').querySelector('.comida').textContent;
            const data = await fetchData(mealType.toLowerCase());
            showData(`Menú de ${mealType}`, data);
        }
    }
}

// Función para editar un ítem del menú
async function editItem(id) {
    const { value: currentData } = await Swal.fire({
        title: 'Editar Menú',
        html: `
            <input id="swal-input-description" class="swal2-input" placeholder="Nueva Descripción">
            <input id="swal-input-date" class="swal2-input" type="date" placeholder="Nueva Fecha">
        `,
        focusConfirm: false,
        preConfirm: () => {
            return {
                description: document.getElementById('swal-input-description').value,
                date: document.getElementById('swal-input-date').value
            }
        },
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar'
    });

    if (currentData) {
        const { description, date } = currentData;
        if (description && date) {
            const payload = { idmenu: id, descripcion: description, fecha: date, action: 'update' };
            if (await sendData(payload)) {
                const mealType = document.querySelector('.feature-box.active').querySelector('.comida').textContent;
                const data = await fetchData(mealType.toLowerCase());
                showData('Menú actualizado', data);
            }
        } else {
            Swal.fire({ title: 'Error', text: 'La descripción y la fecha son requeridos.', icon: 'error' });
        }
    }
}

// Función para enviar datos al servidor
async function sendData(payload) {
    try {
        const response = await fetch('../../php_basesDatos/MenuCRUD.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(payload)
        });
        const result = await response.json();
        Swal.fire({ title: result.success ? 'Éxito' : 'Error', text: result.success || result.error, icon: result.success ? 'success' : 'error' });
        return !!result.success;
    } catch (error) {
        console.error('Error sending data:', error);
        Swal.fire({ title: 'Error', text: 'No se pudo enviar la solicitud. Por favor, intente de nuevo más tarde.', icon: 'error' });
        return false;
    }
}

// Listener para el botón "Agregar Menú"
document.getElementById('addMenuBtn').addEventListener('click', createNewMenu);

// Listener para las cajas principales
document.getElementById('desayunoBox').addEventListener('click', () => handleBoxClick('desayuno'));
document.getElementById('almuerzoBox').addEventListener('click', () => handleBoxClick('almuerzo'));
document.getElementById('refrigerioBox').addEventListener('click', () => handleBoxClick('refrigerio'));

// Función para manejar el clic en las cajas principales
async function handleBoxClick(mealType) {
    const data = await fetchData(mealType);
    showData(`Menú de ${mealType.charAt(0).toUpperCase() + mealType.slice(1)}`, data);
}
