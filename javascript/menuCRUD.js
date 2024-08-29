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
                        <td>${formatDate(item.fecha)}</td>
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

// Función para formatear la fecha y mostrar el día de la semana
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: '2-digit', day: '2-digit', weekday: 'long' };
    return date.toLocaleDateString('es-ES', options).replace(',', '');
}

// Función para obtener la fecha del próximo día especificado
function getNextDate(dayName) {
    const daysOfWeek = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    const today = new Date();
    const todayDayIndex = today.getDay(); // 0 = domingo, 1 = lunes, ..., 6 = sábado
    const targetDayIndex = daysOfWeek.indexOf(dayName.toLowerCase());

    if (targetDayIndex === -1) return null; // Día no válido

    // Calcular la diferencia de días
    let daysUntilTarget = (targetDayIndex - todayDayIndex + 7) % 7;
    if (daysUntilTarget === 0) daysUntilTarget += 7; // Asegurarse de que la fecha sea del próximo día

    const targetDate = new Date(today);
    targetDate.setDate(today.getDate() + daysUntilTarget);
    return targetDate.toISOString().split('T')[0]; // Formato YYYY-MM-DD
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
        const { value: dateInput } = await Swal.fire({
            title: 'Fecha del Menú',
            input: 'text',
            inputLabel: 'Fecha (YYYY-MM-DD) o Nombre del Día',
            showCancelButton: true,
            inputValidator: value => !value && 'La fecha o el nombre del día son requeridos!'
        });

        const date = /^\d{4}-\d{2}-\d{2}$/.test(dateInput) ? dateInput : getNextDate(dateInput);
        if (description && date) {
            const payload = { mealType, descripcion: description, fecha: date, action: 'create' };
            if (await sendData(payload)) {
                const data = await fetchData(mealType);
                showData(`Menú de ${mealType}`, data);
            }
        }
    }
}

// Función para editar un ítem del menú
async function editItem(id) {
    const { value: currentData } = await Swal.fire({
        title: 'Editar Menú',
        html: `
            <input id="swal-input-description" class="swal2-input" placeholder="Nueva Descripción">
            <input id="swal-input-date" class="swal2-input" placeholder="Nueva Fecha (YYYY-MM-DD)">
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
                const mealType = document.querySelector('tr td:first-child').textContent;
                const data = await fetchData(mealType);
                showData('Menú actualizado', data);
            }
        } else {
            Swal.fire({ title: 'Error', text: 'La descripción y la fecha son requeridos.', icon: 'error' });
        }
    }
}

// Función para editar un ítem del menú
async function editItem(id) {
    const { value: currentData } = await Swal.fire({
        title: 'Editar Menú',
        html: `
            <input id="swal-input-description" class="swal2-input" placeholder="Nueva Descripción">
            <input id="swal-input-date" class="swal2-input" placeholder="Nueva Fecha (YYYY-MM-DD o Nombre del Día)">
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
            // Convertir el nombre del día en una fecha válida
            const validDate = /^\d{4}-\d{2}-\d{2}$/.test(date) ? date : getNextDate(date);
            if (validDate) {
                const payload = { idmenu: id, descripcion: description, fecha: validDate, action: 'update' };
                if (await sendData(payload)) {
                    const mealType = document.querySelector('tr td:first-child').textContent;
                    const data = await fetchData(mealType);
                    showData('Menú actualizado', data);
                }
            } else {
                Swal.fire({ title: 'Error', text: 'La fecha o el nombre del día son inválidos.', icon: 'error' });
            }
        } else {
            Swal.fire({ title: 'Error', text: 'La descripción y la fecha son requeridos.', icon: 'error' });
        }
    }
}

// Función para obtener la fecha del próximo día especificado
function getNextDate(dayName) {
    const daysOfWeek = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    const today = new Date();
    const todayDayIndex = today.getDay(); // 0 = domingo, 1 = lunes, ..., 6 = sábado
    const targetDayIndex = daysOfWeek.indexOf(dayName.toLowerCase());

    if (targetDayIndex === -1) return null; // Día no válido

    // Calcular la diferencia de días
    let daysUntilTarget = (targetDayIndex - todayDayIndex + 7) % 7;
    if (daysUntilTarget === 0) daysUntilTarget += 7; // Asegurarse de que la fecha sea del próximo día

    const targetDate = new Date(today);
    targetDate.setDate(today.getDate() + daysUntilTarget);
    return targetDate.toISOString().split('T')[0]; // Formato YYYY-MM-DD
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

// Agregar eventos a los botones
document.getElementById('desayunoBox').addEventListener('click', async () => showData('Menú de Desayuno', await fetchData('desayuno')));
document.getElementById('almuerzoBox').addEventListener('click', async () => showData('Menú de Almuerzo', await fetchData('almuerzo')));
document.getElementById('refrigerioBox').addEventListener('click', async () => showData('Menú de Refrigerio', await fetchData('refrigerio')));
