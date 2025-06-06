import axios from "axios";
import Swal from "sweetalert2";
const API_URL = import.meta.env.VITE_API_URL;
export const handleDownloadExcel = async () => {
  try {
    const response = await axios.get(`${API_URL}/exportExcel.php`, {
      responseType: "blob", // Importante para recibir archivos binarios
    });

    // Crear una URL temporal para el archivo descargado
    const url = window.URL.createObjectURL(new Blob([response.data]));

    // Crear un enlace y simular clic para descargar
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", "estadisticas.xlsx"); // Nombre del archivo
    document.body.appendChild(link);
    link.click();
    link.remove();

    Swal.fire({
      icon: 'success',
      title: '¡Descarga completada!',
      text: 'El archivo Excel se ha descargado correctamente.',
      confirmButtonColor: '#3085d6',
    });
  } catch (error) {
    console.error("Error al descargar Excel:", error);

    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Hubo un problema al generar o descargar el archivo Excel.',
      confirmButtonColor: '#d33',
    });
  }
};
