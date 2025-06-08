import { useEffect, useState, useCallback } from "react";
import { fetchQRScans } from "../../services/qrService";

const QRScannerlist = () => {
  const [qrData, setQrData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  const scansPerPage = 5;

  const loadData = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await fetchQRScans();

      if (!Array.isArray(data)) {
        throw new Error("Formato de datos inválido recibido del servidor");
      }

      setQrData(data);
      setCurrentPage(1);
    } catch (err) {
      console.error("Error al cargar datos:", err);
      setError(err.message || "Error al cargar los datos");
      setQrData([]);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    loadData();
  }, [loadData]);

  // Cálculos de paginación
  const indexOfLastScan = currentPage * scansPerPage;
  const indexOfFirstScan = indexOfLastScan - scansPerPage;
  const currentScans = qrData.slice(indexOfFirstScan, indexOfLastScan);
  const totalPages = Math.max(1, Math.ceil(qrData.length / scansPerPage));

  const handlePageChange = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages) return;
    setCurrentPage(pageNumber);
  };

  const renderTable = () => {
    if (currentScans.length === 0) {
      return (
        <tr>
          <td colSpan="3" className="text-center py-4">
            {error ? error : "No hay registros para mostrar"}
          </td>
        </tr>
      );
    }

    return currentScans.map((item) => {
      // Convertir string de fecha a objeto Date
      const fechaObj = new Date(item.fecha);

      // Formatear fecha y hora, si es válida
      const fechaConHora = isNaN(fechaObj)
        ? item.fecha
        : fechaObj.toLocaleString();

      return (
        <tr
          key={`${item.fecha}-${item.curso}-${item.cantidad}`}
          className="bg-white hover:bg-gray-50 transition duration-150"
        >
          <td className="px-4 py-2 whitespace-nowrap">{fechaConHora}</td>
          <td className="px-4 py-2">{item.curso}</td>
          <td className="px-4 py-2 text-center">{item.cantidad}</td>
        </tr>
      );
    });
  };

  const renderPagination = () => {
    if (totalPages <= 1) return null;

    const pageButtons = [];
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    if (startPage > 1) {
      pageButtons.push(
        <button
          key={1}
          onClick={() => handlePageChange(1)}
          className={`btn btn-sm ${1 === currentPage ? "btn-primary" : "btn-outline"}`}
        >
          1
        </button>
      );
      if (startPage > 2) {
        pageButtons.push(<span key="start-ellipsis" className="px-2">...</span>);
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      pageButtons.push(
        <button
          key={i}
          onClick={() => handlePageChange(i)}
          className={`btn btn-sm ${i === currentPage ? "btn-primary" : "btn-outline"}`}
        >
          {i}
        </button>
      );
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        pageButtons.push(<span key="end-ellipsis" className="px-2">...</span>);
      }
      pageButtons.push(
        <button
          key={totalPages}
          onClick={() => handlePageChange(totalPages)}
          className={`btn btn-sm ${totalPages === currentPage ? "btn-primary" : "btn-outline"}`}
        >
          {totalPages}
        </button>
      );
    }

    return (
      <div className="flex justify-center mt-4 space-x-2">
        <button
          onClick={() => handlePageChange(currentPage - 1)}
          disabled={currentPage === 1}
          className="btn btn-outline btn-sm"
        >
          Anterior
        </button>

        {pageButtons}

        <button
          onClick={() => handlePageChange(currentPage + 1)}
          disabled={currentPage === totalPages}
          className="btn btn-outline btn-sm"
        >
          Siguiente
        </button>
      </div>
    );
  };

  return (
    <div className="container mx-auto mt-4 px-2 sm:px-0">
      <div className="bg-white rounded-lg shadow-sm p-4">
        <h2 className="text-xl font-semibold mb-4 text-gray-800">Registros de QR Escaneados</h2>

        {loading ? (
          <div className="flex justify-center items-center py-8">
            <div className="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
          </div>
        ) : (
          <>
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm text-gray-700">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-4 py-3 font-medium text-gray-700">Fecha</th>
                    <th className="px-4 py-3 font-medium text-gray-700">Curso</th>
                    <th className="px-4 py-3 font-medium text-gray-700 text-center">Cantidad</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-200">
                  {renderTable()}
                </tbody>
              </table>
            </div>

            {renderPagination()}
          </>
        )}
      </div>
    </div>
  );
};

export default QRScannerlist;
