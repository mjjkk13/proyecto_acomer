import { useEffect, useState } from 'react';
import { getQRCodes } from '../../services/qrService';
import Swal from 'sweetalert2';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faQrcode, faEye } from '@fortawesome/free-solid-svg-icons';

const QRCodesList = () => {
  const [qrCodes, setQrCodes] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const codesPerPage = 5; // Cambia este valor para mostrar más o menos filas por página
  const API_URL = 'http://localhost/proyecto_acomer/server/php/qrcodes'; // URL base para las imágenes

  useEffect(() => {
    loadQRCodes();
  }, []);

  const loadQRCodes = async () => {
    try {
      const data = await getQRCodes();
      const qrCodesWithFullImageUrl = data.map(codigo => ({
        ...codigo,
        imagen: `${API_URL}/${codigo.imagen}`,
      }));
      setQrCodes(qrCodesWithFullImageUrl);
      setCurrentPage(1); // Reiniciar a página 1 al cargar datos
    } catch (error) {
      console.error('Error al cargar códigos QR:', error);
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Error al cargar los códigos QR',
      });
    }
  };

  const showLargeImage = (imageUrl) => {
    Swal.fire({
      imageUrl: imageUrl,
      imageAlt: 'Código QR ampliado',
      showCloseButton: true,
      showConfirmButton: false,
      width: 'auto',
    });
  };

  // Paginación: calcular índices de los códigos a mostrar
  const indexOfLastCode = currentPage * codesPerPage;
  const indexOfFirstCode = indexOfLastCode - codesPerPage;
  const currentCodes = qrCodes.slice(indexOfFirstCode, indexOfLastCode);

  const totalPages = Math.ceil(qrCodes.length / codesPerPage);

  const handlePageChange = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages) return;
    setCurrentPage(pageNumber);
  };

  return (
    <div className="container mx-auto mt-4">
      {/* Espacio antes de la tabla */}
      <div className="overflow-x-auto mt-6">
        <table className="w-full text-left text-sm text-gray-700">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-4 py-2 font-semibold">Fecha y Hora</th>
              <th className="px-4 py-2 font-semibold">Curso</th>
              <th className="px-4 py-2 font-semibold">QR</th>
            </tr>
          </thead>
          <tbody>
            {currentCodes.length > 0 ? (
              currentCodes.map((codigo, index) => (
                <tr
                  key={index}
                  className={`${
                    index % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                  } hover:bg-gray-200 transition duration-300`}
                >
                  <td className="px-4 py-2">{codigo.fecha_hora}</td>
                  <td className="px-4 py-2">{codigo.nombrecurso}</td>
                  <td className="px-4 py-2">
                    {codigo.imagen ? (
                      <div className="flex items-center">
                        <img
                          src={codigo.imagen}
                          alt="Código QR"
                          className="w-16 rounded-lg cursor-pointer"
                          onClick={() => showLargeImage(codigo.imagen)}
                        />
                        <FontAwesomeIcon
                          icon={faEye}
                          className="ml-2 text-xl cursor-pointer text-gray-600"
                          onClick={() => showLargeImage(codigo.imagen)}
                        />
                      </div>
                    ) : (
                      <FontAwesomeIcon icon={faQrcode} className="text-2xl text-gray-600" />
                    )}
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="3" className="text-center py-4">
                  No hay códigos QR para mostrar
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Controles de paginación */}
      <div className="flex justify-center mt-4 space-x-2">
        <button
          onClick={() => handlePageChange(currentPage - 1)}
          disabled={currentPage === 1}
          className="btn btn-secondary btn-sm"
        >
          Anterior
        </button>

        {[...Array(totalPages)].map((_, index) => {
          const pageNumber = index + 1;
          return (
            <button
              key={pageNumber}
              onClick={() => handlePageChange(pageNumber)}
              className={`btn btn-sm ${
                currentPage === pageNumber ? 'btn-primary' : 'btn-secondary'
              }`}
            >
              {pageNumber}
            </button>
          );
        })}

        <button
          onClick={() => handlePageChange(currentPage + 1)}
          disabled={currentPage === totalPages}
          className="btn btn-secondary btn-sm"
        >
          Siguiente
        </button>
      </div>
    </div>
  );
};

export default QRCodesList;
