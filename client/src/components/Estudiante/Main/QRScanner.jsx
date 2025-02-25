import Swal from 'sweetalert2';
import { BrowserQRCodeReader } from '@zxing/library'; // Importar el lector QR
import foto from '../../../img/qr.png';
import foto2 from '../../../img/qr2.png';
import { useState, useRef, useEffect, useCallback } from 'react';
import sonido from '../../../assets/sonido.mp3'

const QRScanner = () => {
  const videoRef = useRef(null); // Referencia al elemento <video>
  const [isCameraActive, setIsCameraActive] = useState(false); // Estado para controlar la cámara

  const handleScanClick = () => {
    Swal.fire({
      title: 'Escanea el Código QR',
      text: 'Dale permiso al navegador para usar tu cámara o desactívala si ya no la necesitas',
      showCancelButton: true,
      showConfirmButton: true,
      confirmButtonText: 'Activar cámara',
      cancelButtonText: 'Cancelar',
      imageUrl: foto,
      imageWidth: 100,
      imageHeight: 100,
      imageAlt: 'Imagen de escaneo QR',
    }).then((result) => {
      if (result.isDenied) {
        stopCamera(); // Llama a la función para desactivar la cámara
        Swal.fire('Cámara desactivada', '', 'info');
      } else if (result.isConfirmed) {
        setIsCameraActive(true); // Activa el renderizado del video
      }
    });
  };

  const activateCamera = useCallback(async () => {
    try {
      if (!videoRef.current) {
        throw new Error('El elemento <video> no está disponible. Asegúrate de que esté renderizado.');
      }

      const stream = await navigator.mediaDevices.getUserMedia({ video: true }); // Acceder a la cámara
      videoRef.current.srcObject = stream; // Asignar el flujo de video al elemento <video>
      videoRef.current.play(); // Iniciar la reproducción del video

      const codeReader = new BrowserQRCodeReader();
      codeReader.decodeFromVideoDevice(null, videoRef.current, (result, err) => {
        if (result) {
          console.log('Código QR detectado:', result.text);
          const audio = new Audio(sonido); // Crear una nueva instancia de Audio
          audio.play(); // Reproducir el sonido
          codeReader.reset(); // Detener el lector de QR
          Swal.fire({
            title: 'Código QR detectado',
            text: result.text,
            icon: 'success',
            showConfirmButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false
          });
          stopCamera(); // Detener la cámara después del escaneo
        }
        if (err && !(err.name === 'NotFoundException')) {
          console.error('Error al escanear el QR:', err);
        }
      });
    } catch (err) {
      console.error('Error al acceder a la cámara:', err);
      Swal.fire('Error', 'No se pudo activar la cámara.', 'error');
    }
  }, []);

  const stopCamera = () => {
    const stream = videoRef.current?.srcObject; // Verificar si hay un flujo activo
    if (stream) {
      const tracks = stream.getTracks();
      tracks.forEach((track) => track.stop()); // Detener todos los tracks activos
      videoRef.current.srcObject = null; // Limpiar el flujo de video
    }
    setIsCameraActive(false); // Desactiva el renderizado del video
  };

  // Activar la cámara después de renderizar el <video>
  useEffect(() => {
    if (isCameraActive) {
      activateCamera();
    }
  }, [isCameraActive, activateCamera]);

  // Función para manejar el click en el video y detener la cámara
  const handleVideoClick = () => {
    if (isCameraActive) {
      stopCamera();
      Swal.fire('Cámara desactivada', '', 'info');
    }
  };

  return (
    <div className="container mx-auto p-4 text-center">
      <h3 className="text-lg font-semibold mb-4 text-black">Escanear QR para acceder al comedor escolar</h3>
      <p className="mb-4 text-black">Por favor, escanea el código QR para acceder al comedor escolar.</p>
      {/* Mostrar la imagen si la cámara no está activa */}
      {!isCameraActive && (
        <img
          src={foto2}
          alt="Código QR"
          className="w-40 mx-auto cursor-pointer"
          onClick={handleScanClick}
        />
      )}
      {/* Contenedor de video para mostrar la cámara cuando esté activa */}
      {isCameraActive && (
        <div className="mt-4">
          <video
            ref={videoRef}
            className="w-full max-w-md mx-auto border rounded-md"
            autoPlay
            playsInline
            muted
            onClick={handleVideoClick}
          ></video>
        </div>
      )}
    </div>
  );
};

export default QRScanner;
