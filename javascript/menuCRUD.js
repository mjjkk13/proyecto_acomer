// Función para obtener los datos del menú desde el servidor
async function fetchData(mealType) {
    try {
        const response = await fetch('../../php_basesDatos/MenuCRUD.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                mealType: mealType,
                action: 'read'
            })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        Swal.fire({
            title: 'Error',
            text: 'No se pudieron obtener los datos. Por favor, intente de nuevo más tarde.',
            icon: 'error'
        });
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

    Swal.fire({
        title: title,
        html: table + `<button class="btn btn-success" onclick="createNewMenu()">Agregar Menú</button>`,
        width: '600px',
        showConfirmButton: false
    });
}

// Función para crear un nuevo menú
async function createNewMenu() {
    const { value: mealType } = await Swal.fire({
        title: 'Seleccionar Tipo de Menú',
        input: 'select',
        inputOptions: {
            desayuno: 'Desayuno',
            almuerzo: 'Almuerzo',
            refrigerio: 'Refrigerio'
        },
        inputPlaceholder: 'Seleccionar',
        showCancelButton: true,
        inputValidator: value => {
            if (!value) {
                return 'Debe seleccionar un tipo de menú!';
            }
        }
    });

    if (mealType) {
        const { value: description } = await Swal.fire({
            title: 'Descripción del Nuevo Menú',
            input: 'text',
            inputLabel: 'Descripción',
            showCancelButton: true,
            inputValidator: value => {
                if (!value) {
                    return 'La descripción es requerida!';
                }
            }
        });

        const { value: date } = await Swal.fire({
            title: 'Fecha del Menú',
            input: 'text',
            inputLabel: 'Fecha (YYYY-MM-DD)',
            showCancelButton: true,
            inputValidator: value => {
                if (!value) {
                    return 'La fecha es requerida!';
                }
            }
        });

        if (description && date) {
            const payload = {
                mealType,
                descripcion: description,
                fecha: date,
                action: 'create'
            };
            const success = await sendData(payload);
            if (success) {
                const data = await fetchData(mealType);
                showData(`Menú de ${mealType}`, data);
            }
        }
    }
}

// Función para editar un ítem del menú
async function editItem(id) {
    const { value: description } = await Swal.fire({
        title: 'Editar Descripción del Menú',
        input: 'text',
        inputLabel: 'Nueva Descripción',
        showCancelButton: true,
        inputValidator: value => {
            if (!value) {
                return 'La descripción es requerida!';
            }
        }
    });

    if (description) {
        const payload = { idmenu: id, descripcion: description, action: 'update' };
        const success = await sendData(payload);
        if (success) {
            const mealType = document.querySelector('tr td:first-child').textContent;
            const data = await fetchData(mealType);
            showData('Menú actualizado', data);
        }
    }
}

// Función para eliminar un ítem del menú
async function deleteItem(id) {
    const confirm = await Swal.fire({
        title: '¿Está seguro?',
        text: '¡No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminarlo!',
        cancelButtonText: 'No, cancelar!'
    });

    if (confirm.isConfirmed) {
        const payload = { idmenu: id, action: 'delete' };
        const success = await sendData(payload);
        if (success) {
            const mealType = document.querySelector('tr td:first-child').textContent;
            const data = await fetchData(mealType);
            showData('Menú actualizado', data);
        }
    }
}

// Función para enviar datos al servidor
async function sendData(payload) {
    try {
        const response = await fetch('../../php_basesDatos/MenuCRUD.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(payload)
        });

        const result = await response.json();
        if (result.success) {
            Swal.fire({
                title: 'Éxito',
                text: result.success,
                icon: 'success'
            });
            return true;
        } else {
            Swal.fire({
                title: 'Error',
                text: result.error,
                icon: 'error'
            });
            return false;
        }
    } catch (error) {
        console.error('Error sending data:', error);
        Swal.fire({
            title: 'Error',
            text: 'No se pudo enviar la solicitud. Por favor, intente de nuevo más tarde.',
            icon: 'error'
        });
        return false;
    }
}

// Agregar eventos a los botones
document.getElementById('desayunoBox').addEventListener('click', async () => {
    const data = await fetchData('desayuno');
    showData('Menú de Desayuno', data);
});

document.getElementById('almuerzoBox').addEventListener('click', async () => {
    const data = await fetchData('almuerzo');
    showData('Menú de Almuerzo', data);
});

document.getElementById('refrigerioBox').addEventListener('click', async () => {
    const data = await fetchData('refrigerio');
    showData('Menú de Refrigerio', data);
});
