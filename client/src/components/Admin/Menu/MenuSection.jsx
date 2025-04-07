import { useEffect, useState } from 'react';
import Swal from 'sweetalert2';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import desayunoImg from '../../../img/desayuno.png';
import almuerzoImg from '../../../img/almuerzo.png';
import refrigerioImg from '../../../img/refrigerio-saludable.png';
import { fetchMenus, addMenu, fetchMenuByType, updateMenu, deleteMenu } from '../../services/menuService';

const menuImages = {
  desayuno: desayunoImg,
  almuerzo: almuerzoImg,
  refrigerio: refrigerioImg
};

const showData = (title, data, handleEdit, handleDelete) => {
  Swal.fire({
    title: title,
    html: `
      <div class="text-center">
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-gray-100">
                <th class="border p-2">Fecha</th>
                <th class="border p-2">Descripción</th>
                <th class="border p-2">Acciones</th>
              </tr>
            </thead>
            <tbody>
              ${data.map(menu => `
                <tr class="hover:bg-gray-50">
                  <td class="border p-2">${menu.fecha}</td>
                  <td class="border p-2">${menu.descripcion}</td>
                  <td class="border p-2">
                    <div data-id="${menu.idmenu}">
                      <button class="btn-edit btn btn-sm btn-warning me-2">Editar</button>
                      <button class="btn-delete btn btn-sm btn-danger">Eliminar</button>
                    </div>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `,
    width: '80%',
    confirmButtonText: 'Cerrar',
    didOpen: () => {
      data.forEach(menu => {
        const container = document.querySelector(`div[data-id="${menu.idmenu}"]`);
        if (container) {
          container.querySelector('.btn-edit').onclick = () => handleEdit(menu);
          container.querySelector('.btn-delete').onclick = () => handleDelete(menu.idmenu);
        }
      });
    }
  });
};

const MenuSection = () => {
  const [setGroupedMenus] = useState({});


  useEffect(() => {
    loadMenus();
  }, []);

  const loadMenus = async () => {
    try {
      const data = await fetchMenus();
      const grouped = data.reduce((acc, menu) => {
        const key = menu.tipomenu;
        if (!acc[key]) acc[key] = [];
        acc[key].push(menu);
        return acc;
      }, {});
      setGroupedMenus(grouped);
    } catch (error) {
      console.error('Error loading menus:', error);
    }
  };

  const handleBoxClick = async (mealType) => {
    try {
      const data = await fetchMenuByType(mealType);
      showData(
        `${capitalize(mealType)}`,
        data,
        handleEditMenu,
        handleDeleteMenu
      );
    } catch (error) {
      console.error('Error fetching menu by type:', error);
      Swal.fire('Error', 'No se pudo cargar el menú.', 'error');
    }
  };

  const handleEditMenu = async (menu) => {
    const { value: formValues } = await Swal.fire({
      title: 'Editar Menú',
      html: `
        <select id="tipoMenu" class="swal2-input" style="background-color: white;" value="${menu.tipomenu}">
          <option value="desayuno">Desayuno</option>
          <option value="almuerzo">Almuerzo</option>
          <option value="refrigerio">Refrigerio</option>
        </select>
        <input id="fecha" type="date" class="swal2-input" value="${menu.fecha}">
        <textarea id="descripcion" class="swal2-textarea">${menu.descripcion}</textarea>
      `,
      showCancelButton: true,
      confirmButtonText: 'Actualizar',
      focusConfirm: false,
      preConfirm: () => ({
        idmenu: menu.idmenu,
        tipomenu: document.getElementById('tipoMenu').value,
        fecha: document.getElementById('fecha').value,
        descripcion: document.getElementById('descripcion').value
      })
    });

    if (formValues) {
      try {
        const response = await updateMenu(formValues);
        if (response.success) {
          await loadMenus();
          Swal.fire('Éxito', 'Menú actualizado', 'success');
        }
      } catch (error) {
        console.error('Error updating menu:', error);
        Swal.fire('Error', 'No se pudo actualizar el menú.', 'error');
      }
    }
  };

  const handleDeleteMenu = async (idmenu) => {
    const result = await Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminarlo!'
    });

    if (result.isConfirmed) {
      try {
        const response = await deleteMenu(idmenu);
        if (response.success) {
          await loadMenus();
          Swal.fire('Eliminado!', 'El menú ha sido eliminado.', 'success');
        }
      } catch (error) {
        console.error('Error deleting menu:', error);
        Swal.fire('Error', 'No se pudo eliminar el menú.', 'error');
      }
    }
  };

  const handleAddMenu = async () => {
    const { value: formValues } = await Swal.fire({
      title: 'Agregar Menú',
      html: `
        <select id="tipoMenu" class="swal2-input" style="background-color: white;">
          <option value="desayuno">Desayuno</option>
          <option value="almuerzo">Almuerzo</option>
          <option value="refrigerio">Refrigerio</option>
        </select>
        <input id="fecha" type="date" class="swal2-input">
        <textarea id="descripcion" class="swal2-textarea" placeholder="Descripción"></textarea>
      `,
      showCancelButton: true,
      confirmButtonText: 'Agregar',
      focusConfirm: false,
      preConfirm: () => ({
        tipomenu: document.getElementById('tipoMenu').value,
        fecha: document.getElementById('fecha').value,
        descripcion: document.getElementById('descripcion').value
      })
    });

    if (formValues) {
      try {
        const response = await addMenu(formValues);
        if (response.success) {
          await loadMenus();
          Swal.fire('Éxito', 'Menú agregado', 'success');
        } else {
          Swal.fire('Error', 'No se pudo agregar el menú.', 'error');
        }
      } catch (error) {
        console.error('Error adding menu:', error);
        Swal.fire('Error', 'No se pudo agregar el menú.', 'error');
      }
    }
  };

  return (
    <div className="container mx-auto my-4">
      <h3 className="text-4xl text-center mb-4 mt-6">Menú</h3>
      <section className="grid grid-cols-1 sm:grid-cols-3 gap-4 py-3">
        {['desayuno', 'almuerzo', 'refrigerio'].map((mealType) => (
          <div
            key={mealType}
            className="bg-white p-4 rounded-lg shadow-md flex flex-col items-center justify-between cursor-pointer hover:shadow-lg transition-shadow"
            onClick={() => handleBoxClick(mealType)}
          >
            <img
              src={menuImages[mealType]}
              className="img-fluid"
              alt={mealType}
              style={{ maxHeight: '120px', objectFit: 'contain' }}
              loading="lazy"
            />
            <p className="text-xl text-center mt-2 text-gray-800">{capitalize(mealType)}</p>
          </div>
        ))}
      </section>
      <div className="text-center mt-4 mb-4">
        <button
          className="btn btn-primary w-full sm:w-auto px-6 py-2 hover:bg-blue-600 transition-colors"
          onClick={handleAddMenu}
        >
          <FontAwesomeIcon icon={faPlus} className="me-2" />
          Agregar Menú
        </button>
      </div>
    </div>
  );
};

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

export default MenuSection;