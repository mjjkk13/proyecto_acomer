import { useEffect, useState } from 'react';
import { getQRCodes, deleteQRCode } from '../../services/qrService';
import Swal from 'sweetalert2';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faQrcode } from '@fortawesome/free-solid-svg-icons';

const QR = () => {
  const [qrCodes, setQrCodes] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 5;

  useEffect(() => {
    loadQRCodes();
  }, []);

  const loadQRCodes = async () => {
    try {
      const response = await getQRCodes();
      console.log('Respuesta getQRCodes:', response);

      const data = Array.isArray(response) ? response : response.data || [];

      if (!Array.isArray(data)) {
        throw new Error('Los datos recibidos no son un arreglo');
      }

  const fullQRs = data.map((codigo) => ({
    id: codigo.idqrgenerados,
    nombrecurso: codigo.nombrecurso || 'Sin curso asignado',
    fecha_hora: codigo.fechageneracion,
    imagen: codigo.qr_image || null,  // <-- aquí igual, usa qr_image
  }));



      setQrCodes(fullQRs);
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
        setQrCodes((prev) => prev.filter((q) => q.id !== id));
        Swal.fire('Eliminado', 'El QR fue eliminado.', 'success');
      } catch {
        Swal.fire('Error', 'No se pudo eliminar el QR', 'error');
      }
    }
  };

  // Paginación
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = qrCodes.slice(indexOfFirstItem, indexOfLastItem);
  const totalPages = Math.ceil(qrCodes.length / itemsPerPage);

  const goToPage = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages) return;
    setCurrentPage(pageNumber);
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
            {currentItems.map((codigo, index) => (
              <tr
                key={codigo.id}
                className={`${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} hover:bg-gray-200`}
              >
                <td className="px-4 py-2">
                  {new Date(codigo.fecha_hora).toLocaleDateString()}{' '}
                  {new Date(codigo.fecha_hora).toLocaleTimeString()}
                </td>
                <td className="px-4 py-2">{codigo.nombrecurso}</td>
                <td className="px-4 py-2">
                  {codigo.imagen ? (
                    <img
                      src={codigo.imagen}
                      alt="QR"
                      className="w-16 h-16 object-contain rounded-lg"
                      onError={(e) => {
                        e.target.onerror = null;
                        e.target.src = '';
                      }}
                    />
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

      {/* Controles de paginación */}
      <div className="mt-4 flex justify-center space-x-2">
        <button
          onClick={() => goToPage(currentPage - 1)}
          disabled={currentPage === 1}
          className="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
        >
          Anterior
        </button>

        {[...Array(totalPages)].map((_, i) => (
          <button
            key={i}
            onClick={() => goToPage(i + 1)}
            className={`px-3 py-1 rounded ${
              currentPage === i + 1
                ? 'bg-blue-500 text-white'
                : 'bg-gray-200 hover:bg-gray-300'
            }`}
          >
            {i + 1}
          </button>
        ))}

        <button
          onClick={() => goToPage(currentPage + 1)}
          disabled={currentPage === totalPages}
          className="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
        >
          Siguiente
        </button>
      </div>
    </div>
  );
};

export default QR;
