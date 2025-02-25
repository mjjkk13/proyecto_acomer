import { useState, useEffect } from "react";

const DatosPersonales = () => {
  const [userData, setUserData] = useState(null);
  const [errorMessage, setErrorMessage] = useState("");
  const [isEditing, setIsEditing] = useState(false);
  const [editableData, setEditableData] = useState({});

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch(
          "http://localhost/proyecto_acomer/server/php/cargarDatosPersonales.php", {
            credentials: 'include'  // Incluir cookies en la solicitud
          }
        );
        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status}`);
        }
        const data = await response.json();

        if (data.status === "error") {
          setErrorMessage(data.message || "Error desconocido");
          setUserData(null);
        } else {
          setUserData(data);
          setEditableData(data);  // Inicializamos los datos editables con los datos cargados
          setErrorMessage("");
        }
      } catch (error) {
        setErrorMessage("Error al cargar los datos: " + error.message);
      }
    };

    fetchData();
  }, []);

  const handleEditClick = () => {
    setIsEditing(true);
  };

  const handleCancelClick = () => {
    setIsEditing(false);
    setEditableData(userData);  // Restablecer los datos a los valores originales
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setEditableData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  const handleSaveClick = async () => {
    try {
      const response = await fetch(
        "http://localhost/proyecto_acomer/server/php/ActDatosPersonales.php", 
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          credentials: 'include', // Asegurar el envío de cookies de sesión
          body: JSON.stringify(editableData),
        }
      );
      

      if (!response.ok) {
        throw new Error("Error al guardar los datos");
      }

      const data = await response.json();
      if (data.status === "success") {
        setUserData(editableData);
        setIsEditing(false);
      } else {
        setErrorMessage(data.message || "Error desconocido");
      }
    } catch (error) {
      setErrorMessage("Error al guardar los datos: " + error.message);
    }
  };

  return (
    <div className="bg-[#DDE6ED] flex items-center justify-center h-auto py-6">
      <div className="bg-white shadow-xl rounded-lg w-full max-w-4xl p-6">
        {/* Encabezado */}
        <h2 className="text-center text-2xl sm:text-3xl font-bold bg-[#27374D] text-white py-4 rounded-t-lg">
          Datos Personales
        </h2>

        {/* Mostrar mensaje de error si lo hay */}
        {errorMessage && (
          <div className="text-center text-red-500 py-4">{errorMessage}</div>
        )}

        {/* Tabla */}
        <div className="overflow-x-auto mt-4">
          <table className="w-full border-collapse">
            <thead className="bg-[#27374D] text-white">
              <tr>
                <th className="border border-gray-300 px-4 py-3 text-left text-white">Campo</th>
                <th className="border border-gray-300 px-4 py-3 text-left text-white">Información</th>
              </tr>
            </thead>
            <tbody>
              {userData ? (
                <>
                  <tr>
                    <td className="border border-gray-300 px-4 py-3 text-black">Nombre</td>
                    <td className="border border-gray-300 px-4 py-3 text-black">
                      {isEditing ? (
                        <input
                          type="text"
                          name="nombre"
                          value={editableData.nombre || ""}
                          onChange={handleChange}
                          className="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-black focus:outline-none focus:ring-2 focus:ring-[#27374D]"
                          />
                      ) : (
                        userData.nombre
                      )}
                    </td>
                  </tr>
                  <tr>
                    <td className="border border-gray-300 px-4 py-3 text-black">Apellido</td>
                    <td className="border border-gray-300 px-4 py-3 text-black">
                      {isEditing ? (
                        <input
                        type="text"
                        name="apellido"
                        value={editableData.apellido || ""}
                        onChange={handleChange}
                        className="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-black focus:outline-none focus:ring-2 focus:ring-[#27374D]"
                      />
                      ) : (
                        userData.apellido
                      )}
                    </td>
                  </tr>
                  <tr>
                    <td className="border border-gray-300 px-4 py-3 text-black">Email</td>
                    <td className="border border-gray-300 px-4 py-3 text-black">
                      {isEditing ? (
                       <input
                       type="email"
                       name="email"
                       value={editableData.email || ""}
                       onChange={handleChange}
                       className="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-black focus:outline-none focus:ring-2 focus:ring-[#27374D]"
                     />
                      ) : (
                        userData.email
                      )}
                    </td>
                  </tr>
                  <tr>
                    <td className="border border-gray-300 px-4 py-3 text-black">Teléfono</td>
                    <td className="border border-gray-300 px-4 py-3 text-black">
                      {isEditing ? (
                        <input
                          type="text"
                          name="telefono"
                          value={editableData.telefono || ""}
                          onChange={handleChange}
                          className="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-black focus:outline-none focus:ring-2 focus:ring-[#27374D]"
                          />
                      ) : (
                        userData.telefono
                      )}
                    </td>
                  </tr>
                  <tr>
                    <td className="border border-gray-300 px-4 py-3 text-black">Dirección</td>
                    <td className="border border-gray-300 px-4 py-3 text-black">
                      {isEditing ? (
                        <input
                          type="text"
                          name="direccion"
                          value={editableData.direccion || ""}
                          onChange={handleChange}
                          className="w-full px-2 py-1 border border-gray-300 rounded-md bg-gray-100 text-black focus:outline-none focus:ring-2 focus:ring-[#27374D]"
                          />
                      ) : (
                        userData.direccion
                      )}
                    </td>
                  </tr>
                </>
              ) : !errorMessage ? (
                <tr>
                  <td colSpan="2" className="text-center text-gray-500 py-6 text-xl text-black">
                    Cargando datos...
                  </td>
                </tr>
              ) : null}
            </tbody>
          </table>
        </div>

        {/* Botones */}
        <div className="flex justify-end mt-6">
          {isEditing ? (
            <>
              <button
                onClick={handleSaveClick}
                className="bg-[#27374D] text-white py-2 px-6 rounded-md hover:bg-[#1f2b3d] transition duration-300 mr-2"
              >
                Guardar Cambios
              </button>
              <button
                onClick={handleCancelClick}
                className="bg-gray-300 text-black py-2 px-6 rounded-md hover:bg-gray-400 transition duration-300"
              >
                Cancelar
              </button>
            </>
          ) : (
            <button
              onClick={handleEditClick}
              className="bg-[#27374D] text-white py-2 px-6 rounded-md hover:bg-[#1f2b3d] transition duration-300"
            >
              <i className="fas fa-edit mr-2"></i> Editar Datos
            </button>
          )}
        </div>
      </div>
    </div>
  );
};

export default DatosPersonales;
