import { useEffect, useState } from "react";
import { fetchQRScans } from "../../services/qrService";

const QRScannerlist = () => {
  const [qrData, setQrData] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const scansPerPage = 5; // Cambia este valor para ajustar filas por página

  useEffect(() => {
    const cargarDatos = async () => {
      try {
        const data = await fetchQRScans();
        setQrData(data);
        setCurrentPage(1); // Reiniciar a página 1 al cargar datos
      } catch (error) {
        console.error("Error al cargar los datos QR:", error);
      }
    };

    cargarDatos();
  }, []);

  // Cálculo para paginación
  const indexOfLastScan = currentPage * scansPerPage;
  const indexOfFirstScan = indexOfLastScan - scansPerPage;
  const currentScans = qrData.slice(indexOfFirstScan, indexOfLastScan);

  const totalPages = Math.ceil(qrData.length / scansPerPage);

  const handlePageChange = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages) return;
    setCurrentPage(pageNumber);
  };

  return (
    <div className="container mx-auto mt-4">
      <h2 className="text-xl font-semibold mb-4">Registros de QR Escaneados</h2>
      <div className="overflow-x-auto mt-6">
        <table className="w-full text-left text-sm text-gray-700">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-4 py-2 font-semibold">Fecha</th>
              <th className="px-4 py-2 font-semibold">Curso</th>
              <th className="px-4 py-2 font-semibold">Cantidad estudiantes</th>
            </tr>
          </thead>
          <tbody>
            {currentScans.length > 0 ? (
              currentScans.map((item, index) => (
                <tr
                  key={index}
                  className="bg-white hover:bg-gray-200 transition duration-300"
                >
                  <td className="px-4 py-2">{item.fecha}</td>
                  <td className="px-4 py-2">{item.curso}</td>
                  <td className="px-4 py-2">{item.cantidad}</td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="3" className="text-center py-4">
                  No hay registros para mostrar
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
                currentPage === pageNumber ? "btn-primary" : "btn-secondary"
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

export default QRScannerlist;
