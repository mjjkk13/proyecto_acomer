import { useEffect, useState } from "react";
import { fetchQRScans } from "../../services/qrService";

const QRScannerlist = () => {
  const [qrData, setQrData] = useState([]);

  useEffect(() => {
    const cargarDatos = async () => {
      try {
        const data = await fetchQRScans();
        setQrData(data);
      } catch (error) {
        console.error("Error al cargar los datos QR:", error);
      }
    };

    cargarDatos();
  }, []);

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
            {qrData.map((item, index) => (
              <tr
                key={index}
                className="bg-white hover:bg-gray-200 transition duration-300"
              >
                <td className="px-4 py-2">{item.fecha}</td>
                <td className="px-4 py-2">{item.curso}</td>
                <td className="px-4 py-2">{item.cantidad}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default QRScannerlist;
