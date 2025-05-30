import { useState } from 'react';
import Swal from 'sweetalert2';
import desayunoImg from '../../../img/desayuno.png';
import almuerzoImg from '../../../img/almuerzo.png';
import refrigerioImg from '../../../img/refrigerio-saludable.png';
import { fetchMenuByType } from '../../services/menuService';

const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

const menuImages = {
  desayuno: desayunoImg,
  almuerzo: almuerzoImg,
  refrigerio: refrigerioImg
};

const showData = (title, data) => {
  if (!data || data.length === 0) {
    Swal.fire({
      title: title,
      text: 'No hay datos disponibles para este menú',
      icon: 'info'
    });
    return;
  }

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
              </tr>
            </thead>
            <tbody>
              ${data.map(menu => `
                <tr class="hover:bg-gray-50">
                  <td class="border p-2">${menu.fecha}</td>
                  <td class="border p-2">${menu.descripcion}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `,
    width: '80%',
    confirmButtonText: 'Cerrar'
  });
};

const MenuSection = () => {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleBoxClick = async (mealType) => {
    try {
      setLoading(true);
      setError(null);
      const data = await fetchMenuByType(mealType);
      showData(`${capitalize(mealType)}`, data);
    } catch (err) {
      console.error('Error fetching menu by type:', err);
      setError('Error al cargar el menú');
      Swal.fire('Error', 'No se pudo cargar el menú.', 'error');
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="container mx-auto my-4 text-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mx-auto"></div>
        <p className="mt-2">Cargando menú...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="container mx-auto my-4 text-center text-red-500">
        <p>{error}</p>
        <button 
          onClick={() => setError(null)}
          className="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          Volver
        </button>
      </div>
    );
  }

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
    </div>
  );
};

export default MenuSection;