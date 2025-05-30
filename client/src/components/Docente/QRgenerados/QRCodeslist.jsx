import { useEffect, useState } from 'react';
import { getQRCodes } from '../../services/qrService';
import Swal from 'sweetalert2';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faQrcode, faEye, faSyncAlt, faUserShield } from '@fortawesome/free-solid-svg-icons';

const QRCodesList = () => {
  const [qrCodes, setQrCodes] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const [userRole, setUserRole] = useState('user');
  const codesPerPage = 5;
  const API_URL = 'http://localhost/proyecto_acomer/server/php/qrcodes';

  useEffect(() => {
    loadQRCodes();
  }, []);

  const loadQRCodes = async () => {
    setIsLoading(true);
    setError(null);
    
    try {
      const response = await getQRCodes();
      console.log('Respuesta completa del servidor:', response); // Para depuración
      
      // Manejo flexible de la respuesta
      let dataToProcess = [];
      let role = 'user';

      // Diferentes patrones de respuesta posibles
      if (Array.isArray(response)) {
        // Caso 1: La respuesta ES el array de datos
        dataToProcess = response;
      } else if (response && Array.isArray(response.data)) {
        // Caso 2: La respuesta tiene propiedad data con array
        dataToProcess = response.data;
        role = response.role || role;
      } else if (response && response.data && !Array.isArray(response.data)) {
        // Caso 3: La respuesta tiene data pero no es array (convertir a array)
        dataToProcess = [response.data];
        role = response.role || role;
      } else if (response && typeof response === 'object') {
        // Caso 4: Respuesta inesperada pero podría contener códigos QR directamente
        dataToProcess = Object.values(response).filter(item => 
          item && (item.idqrgenerados || item.codigoqr)
        );
        
        if (dataToProcess.length === 0) {
          throw new Error('Formato de respuesta no reconocido');
        }
      } else {
        throw new Error('Respuesta del servidor no válida');
      }

      // Si no hay datos pero la respuesta fue exitosa
      if (dataToProcess.length === 0) {
        setQrCodes([]);
        setUserRole(role);
        Swal.fire({
          icon: 'info',
          title: 'No hay códigos QR',
          text: 'No se encontraron códigos QR registrados',
        });
        return;
      }

      const formattedCodes = dataToProcess.map(item => ({
        id: item.idqrgenerados || Math.random().toString(36).substr(2, 9),
        fecha_hora: item.fechageneracion || new Date().toISOString(),
        nombrecurso: item.nombrecurso || 'Sin curso asignado',
        imagen: item.codigoqr ? `${API_URL}/${item.codigoqr}` : null,
        fecha_uso: item.fecha_uso || null,
        docente: item.docente_nombre || 'Desconocido'
      }));

      setQrCodes(formattedCodes);
      setUserRole(role);
      setCurrentPage(1);
      
    } catch (error) {
      console.error('Error completo:', error);
      const errorMessage = error.response?.data?.message || 
                         error.message || 
                         'Error al cargar los códigos QR';
      setError(errorMessage);
      
      if (error.message === 'authentication_required' || error.response?.status === 401) {
        Swal.fire({
          icon: 'warning',
          title: 'Sesión requerida',
          text: 'Debes iniciar sesión para ver los códigos QR',
          footer: 'Serás redirigido al login'
        }).then(() => {
          window.location.href = '/login';
        });
        return;
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMessage.includes('no reconocido') 
          ? 'El servidor respondió con un formato inesperado' 
          : errorMessage,
        footer: 'Por favor, intente nuevamente más tarde'
      });
    } finally {
      setIsLoading(false);
    }
  };

  const showLargeImage = (imageUrl) => {
    if (!imageUrl) {
      Swal.fire({
        icon: 'warning',
        title: 'Imagen no disponible',
        text: 'El código QR no tiene imagen asociada',
      });
      return;
    }

    Swal.fire({
      imageUrl: imageUrl,
      imageAlt: 'Código QR ampliado',
      showCloseButton: true,
      showConfirmButton: false,
      width: 'auto',
      background: 'transparent',
      imageHeight: 300,
      imageWidth: 300
    });
  };

  const indexOfLastCode = currentPage * codesPerPage;
  const indexOfFirstCode = indexOfLastCode - codesPerPage;
  const currentCodes = qrCodes.slice(indexOfFirstCode, indexOfLastCode);
  const totalPages = Math.ceil(qrCodes.length / codesPerPage);

  const handlePageChange = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages) return;
    setCurrentPage(pageNumber);
  };

  if (isLoading) {
    return (
      <div className="flex justify-center items-center h-64">
        <FontAwesomeIcon 
          icon={faSyncAlt} 
          spin 
          className="text-blue-500 text-4xl" 
        />
        <span className="ml-3 text-gray-600">Cargando códigos QR...</span>
      </div>
    );
  }

  if (error) {
    return (
      <div className="container mx-auto mt-4 p-4 bg-red-50 border-l-4 border-red-500">
        <h3 className="text-red-800 font-bold">Error al cargar datos</h3>
        <p className="text-red-600">{error}</p>
        <button
          onClick={loadQRCodes}
          className="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
        >
          <FontAwesomeIcon icon={faSyncAlt} className="mr-2" />
          Reintentar
        </button>
      </div>
    );
  }

  return (
    <div className="container mx-auto p-4">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-semibold text-gray-800">
          {userRole === 'administrador' ? (
            <>
              <FontAwesomeIcon icon={faUserShield} className="mr-2 text-purple-500" />
              Todos los Códigos QR
            </>
          ) : (
            'Mis Códigos QR'
          )}
          <span className="ml-2 bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-sm">
            {qrCodes.length} registros
          </span>
        </h2>
        <button
          onClick={loadQRCodes}
          className="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center"
        >
          <FontAwesomeIcon icon={faSyncAlt} className="mr-2" />
          Actualizar lista
        </button>
      </div>

      <div className="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                Fecha y Hora
              </th>
              {userRole === 'administrador' && (
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                  Docente
                </th>
              )}
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                Curso
              </th>
              <th className="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                Código QR
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {currentCodes.length > 0 ? (
              currentCodes.map((codigo) => (
                <tr key={codigo.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="text-sm font-medium text-gray-900">
                      {codigo.fecha_hora ? new Date(codigo.fecha_hora).toLocaleDateString() : 'Fecha no disponible'}
                    </div>
                    <div className="text-sm text-gray-500">
                      {codigo.fecha_hora ? new Date(codigo.fecha_hora).toLocaleTimeString() : 'Hora no disponible'}
                    </div>
                  </td>
                  {userRole === 'administrador' && (
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm font-medium text-gray-900">
                        {codigo.docente}
                      </div>
                    </td>
                  )}
                  <td className="px-6 py-4 whitespace-nowrap">
                    <div className="text-sm font-medium text-gray-900">
                      {codigo.nombrecurso}
                    </div>
                  </td>
                  <td className="px-6 py-4 flex justify-center">
                    <div className="flex items-center space-x-3">
                      {codigo.imagen ? (
                        <>
                          <div className="relative group">
                            <img
                              src={codigo.imagen}
                              alt={`QR ${codigo.nombrecurso}`}
                              className="w-12 h-12 object-contain cursor-pointer hover:opacity-75 transition-opacity"
                              onClick={() => showLargeImage(codigo.imagen)}
                            />
                            <div className="absolute inset-0 bg-blue-500 bg-opacity-0 group-hover:bg-opacity-10 transition-all rounded"></div>
                          </div>
                          <button
                            onClick={() => showLargeImage(codigo.imagen)}
                            className="text-blue-500 hover:text-blue-700 transition-colors p-2 rounded-full hover:bg-blue-50"
                            title="Ampliar QR"
                          >
                            <FontAwesomeIcon icon={faEye} size="lg" />
                          </button>
                        </>
                      ) : (
                        <div className="w-12 h-12 flex items-center justify-center bg-gray-100 rounded">
                          <span className="text-gray-400">
                            <FontAwesomeIcon icon={faQrcode} size="lg" />
                          </span>
                        </div>
                      )}
                    </div>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan={userRole === 'administrador' ? 4 : 3} className="px-6 py-8 text-center">
                  <div className="flex flex-col items-center justify-center text-gray-500">
                    <FontAwesomeIcon icon={faQrcode} className="text-4xl mb-3 opacity-50" />
                    <p className="text-lg">No se encontraron códigos QR registrados</p>
                    <button
                      onClick={loadQRCodes}
                      className="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
                    >
                      <FontAwesomeIcon icon={faSyncAlt} className="mr-2" />
                      Intentar nuevamente
                    </button>
                  </div>
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {totalPages > 1 && (
        <div className="mt-6 flex justify-center">
          <nav className="flex items-center space-x-1">
            <button
              onClick={() => handlePageChange(currentPage - 1)}
              disabled={currentPage === 1}
              className={`px-3 py-1 rounded border ${currentPage === 1 ? 'text-gray-400 border-gray-200 cursor-not-allowed' : 'text-gray-700 border-gray-300 hover:bg-gray-100'}`}
            >
              &larr; Anterior
            </button>
            
            {Array.from({ length: Math.min(totalPages, 5) }, (_, i) => {
              let pageNum;
              if (totalPages <= 5) {
                pageNum = i + 1;
              } else if (currentPage <= 3) {
                pageNum = i + 1;
              } else if (currentPage >= totalPages - 2) {
                pageNum = totalPages - 4 + i;
              } else {
                pageNum = currentPage - 2 + i;
              }

              return (
                <button
                  key={pageNum}
                  onClick={() => handlePageChange(pageNum)}
                  className={`px-3 py-1 rounded border ${currentPage === pageNum ? 'bg-blue-500 text-white border-blue-500' : 'text-gray-700 border-gray-300 hover:bg-gray-100'}`}
                >
                  {pageNum}
                </button>
              );
            })}
            
            {totalPages > 5 && (
              <span className="px-2 py-1 text-gray-500">...</span>
            )}
            
            <button
              onClick={() => handlePageChange(currentPage + 1)}
              disabled={currentPage === totalPages}
              className={`px-3 py-1 rounded border ${currentPage === totalPages ? 'text-gray-400 border-gray-200 cursor-not-allowed' : 'text-gray-700 border-gray-300 hover:bg-gray-100'}`}
            >
              Siguiente &rarr;
            </button>
          </nav>
        </div>
      )}
    </div>
  );
};

export default QRCodesList;