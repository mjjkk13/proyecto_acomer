import Swal from 'sweetalert2';
import { BrowserQRCodeReader } from '@zxing/library'; // Importar el lector QR
import foto from '../../../img/qr.png';
import foto2 from '../../../img/qr2.png';
import { useState, useRef, useEffect, useCallback } from 'react';
import sonido from '../../../assets/sonido.mp3';
import saveqr from '../../services/saveqr'; // El servicio para interactuar con el backend

const QRScanner = () => {
  const videoRef = useRef(null); // Referencia al elemento <video>
  const [isCameraActive, setIsCameraActive] = useState(false); // Estado para controlar la cámara
  const [error] = useState(null); // Estado para manejar errores

  // Función para validar si la hora está dentro del rango permitido
  const isWithinAllowedTime = () => {
    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();

    const toMinutes = (h, m) => h * 60 + m;
    const currentMinutes = toMinutes(hours, minutes);

    // Rango de desayuno: 7:00–9:00
    const isBreakfast = currentMinutes >= toMinutes(7, 0) && currentMinutes <= toMinutes(9, 0);

    // Rango de almuerzo: 11:30–13:00
    const isLunch = currentMinutes >= toMinutes(11, 30) && currentMinutes <= toMinutes(13, 0);

    // Rango de refrigerio antes de 13:00
    const isSnack = currentMinutes < toMinutes(13, 0);

    return isBreakfast || isLunch || isSnack;
  };

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
      codeReader.decodeFromVideoDevice(null, videoRef.current, async (result, err) => {
        if (result) {
          if (!isWithinAllowedTime()) {
            codeReader.reset(); // Detiene el lector
            Swal.fire({
              title: 'Escaneo fuera del horario permitido',
              text: 'Solo se puede registrar entre 7:00–9:00am (desayuno), 11:30am–1:00pm (almuerzo) o refrigerio antes de la 1:00pm.',
              icon: 'error',
              confirmButtonText: 'Aceptar',
            });
            stopCamera(); // Apaga la cámara
            return;
          }

          console.log('Código QR detectado:', result.text);
          const audio = new Audio(sonido);
          audio.play();
          codeReader.reset();

          Swal.fire({
            title: 'Código QR detectado',
            text: result.text,
            icon: 'success',
            showConfirmButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
          });

          try {
            await saveqr.saveQRCode(result.text);
          } catch (error) {
            console.error('Error al guardar el QR:', error);
            Swal.fire('Error', 'No se pudo guardar el QR en la base de datos.', 'error');
          }

          stopCamera();
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
    const stream = videoRef.current?.srcObject;
    if (stream) {
      const tracks = stream.getTracks();
      tracks.forEach((track) => track.stop());
      videoRef.current.srcObject = null;
    }
    setIsCameraActive(false);
  };

  useEffect(() => {
    if (isCameraActive) {
      activateCamera();
    }
  }, [isCameraActive, activateCamera]);

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

      {error && <div className="text-red-500 mb-4">{error}</div>}

      {!isCameraActive && (
        <img
          src={foto2}
          alt="Código QR"
          className="w-40 mx-auto cursor-pointer"
          onClick={handleScanClick}
        />
      )}

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
