import { useEffect, useState } from 'react';
import { getQRCodes, deleteQRCode } from '../../services/qrService';
import Swal from 'sweetalert2';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faQrcode } from '@fortawesome/free-solid-svg-icons';

const QR = () => {
  const [qrCodes, setQrCodes] = useState([]);
  const API_URL = 'http://localhost/proyecto_acomer/server/php/qrcodes';

  useEffect(() => {
    loadQRCodes();
  }, []);

  const loadQRCodes = async () => {
    try {
      const data = await getQRCodes();
      const fullURLs = data.map(codigo => ({
        ...codigo,
        imagen: `${API_URL}/${codigo.imagen}`,
      }));
      setQrCodes(fullURLs);
    } catch (error) {
      console.error('Error al cargar QR:', error);
      Swal.fire('Error', 'No se pudieron cargar los códigos QR', 'error');
    }
  };

  const handleDelete = async (id) => {
    const result = await Swal.fire({
      title: '¿Eliminar QR?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    });

    if (result.isConfirmed) {
      try {
        await deleteQRCode(id);
        setQrCodes(qrCodes.filter((q) => q.id !== id));
        Swal.fire('Eliminado', 'El QR fue eliminado.', 'success');
      } catch {
        Swal.fire('Error', 'No se pudo eliminar el QR', 'error');
      }
    }
  };

  return (
    <div className="container mx-auto mt-4">
      <h2 className="text-xl font-bold mb-4">Listado de Códigos QR</h2>

      <div className="overflow-x-auto mt-6">
        <table className="w-full text-left text-sm text-gray-700">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-4 py-2">Fecha y Hora</th>
              <th className="px-4 py-2">Curso</th>
              <th className="px-4 py-2">QR</th>
              <th className="px-4 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            {qrCodes.map((codigo, index) => (
              <tr
                key={index}
                className={`${
                  index % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                } hover:bg-gray-200`}
              >
                <td className="px-4 py-2">{codigo.fecha_hora}</td>
                <td className="px-4 py-2">{codigo.nombrecurso}</td>
                <td className="px-4 py-2">
                  {codigo.imagen ? (
                    <img src={codigo.imagen} alt="QR" className="w-16 rounded-lg" />
                  ) : (
                    <FontAwesomeIcon icon={faQrcode} className="text-2xl text-gray-600" />
                  )}
                </td>
                <td className="px-4 py-2">
                  <button
                    onClick={() => handleDelete(codigo.id)}
                    className="text-red-600 font-semibold hover:underline"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default QR;
