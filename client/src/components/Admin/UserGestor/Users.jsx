import { useEffect, useState } from "react";
import { fetchUsers, updateUser, deleteUser } from "../../services/userService";
import Swal from "sweetalert2";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPencilAlt, faTrashAlt } from "@fortawesome/free-solid-svg-icons";

const Users = () => {
  const [usuarios, setUsuarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  // Paginación
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 5;
  const totalPages = Math.ceil(usuarios.length / itemsPerPage);
  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = usuarios.slice(indexOfFirstItem, indexOfLastItem);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await fetchUsers();
      setUsuarios(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error("Error loading data:", error);
      setError("No se pudo cargar los usuarios");
      Swal.fire("Error", "No se pudo cargar los usuarios", "error");
    } finally {
      setLoading(false);
    }
  };

  const goToPage = (pageNumber) => {
    if (pageNumber < 1 || pageNumber > totalPages) return;
    setCurrentPage(pageNumber);
  };

  const handleEdit = async (id) => {
    const selectedUser = usuarios.find((u) => u.idcredenciales === id);
    if (!selectedUser) return;

    const { value: formValues } = await Swal.fire({
      title: "Editar Credencial",
      html: `
        <div class="flex flex-col gap-2">
          <input type="text" id="editUser" class="swal2-input" placeholder="Usuario" value="${selectedUser.nombre_usuario}">
          <input type="password" id="editPassword" class="swal2-input" placeholder="Nueva Contraseña">
          <select id="editStatus" class="swal2-select">
            <option value="1" ${selectedUser.estado == 1 ? "selected" : ""}>Activo</option>
            <option value="0" ${selectedUser.estado == 0 ? "selected" : ""}>Inactivo</option>
          </select>
          <select id="editRol" class="swal2-select">
            <option value="Estudiante SS" ${selectedUser.rol === "Estudiante SS" ? "selected" : ""}>Estudiante SS</option>
            <option value="Docente" ${selectedUser.rol === "Docente" ? "selected" : ""}>Docente</option>
            <option value="Administrador" ${selectedUser.rol === "Administrador" ? "selected" : ""}>Administrador</option>
          </select>
        </div>
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: "Guardar",
      preConfirm: () => ({
        user: document.getElementById("editUser").value.trim(),
        password: document.getElementById("editPassword").value.trim(),
        status: document.getElementById("editStatus").value,
        rol: document.getElementById("editRol").value,
      }),
    });

    if (formValues) {
      try {
        const updateData = {
          id,
          user: formValues.user,
          status: formValues.status,
          rol: formValues.rol,
          ...(formValues.password && { password: formValues.password }),
        };

        const result = await updateUser(updateData);
        if (result.success) {
          Swal.fire("Actualizado!", "La credencial ha sido actualizada.", "success");
          loadData();
        } else {
          throw new Error(result.error || "No se pudo actualizar el usuario");
        }
      } catch (error) {
        Swal.fire("Error", error.message, "error");
      }
    }
  };

  const handleDelete = async (id) => {
    const result = await Swal.fire({
      title: "¿Eliminar Credencial?",
      text: "Esta acción no se puede revertir!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar!",
      cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
      try {
        const response = await deleteUser(id);
        if (response.success) {
          Swal.fire("Eliminado!", "La credencial ha sido eliminada.", "success");
          loadData();
        } else {
          throw new Error(response.error || "No se pudo eliminar el usuario");
        }
      } catch (error) {
        Swal.fire("Error", error.message, "error");
      }
    }
  };

  return (
    <div className="p-4">
      <h2 className="text-2xl font-bold mb-4 text-[#27374D]">Lista de Usuarios</h2>

      {loading && <p className="text-blue-500">Cargando usuarios...</p>}
      {error && <p className="text-red-500">{error}</p>}

      <div className="overflow-x-auto shadow-lg rounded-lg">
        <table className="table w-full">
          <thead className="bg-gray-200">
            <tr>
              <th className="text-[#27374D]">Usuario</th>
              <th className="text-[#27374D]">Rol</th>
              <th className="text-[#27374D]">Estado</th>
              <th className="text-[#27374D]">Último Acceso</th>
              <th className="text-[#27374D]">Acciones</th>
            </tr>
          </thead>
          <tbody className="bg-white text-gray-900">
            {currentItems.length > 0 ? (
              currentItems.map((usuario) => (
                <tr key={usuario.idcredenciales}>
                  <td>{usuario.nombre_usuario}</td>
                  <td>{usuario.rol}</td>
                  <td>
                    <span className={`font-bold ${usuario.estado == 1 ? "text-green-500" : "text-red-500"}`}>
                      {usuario.estado == 1 ? "ACTIVO" : "INACTIVO"}
                    </span>
                  </td>
                  <td>{usuario.ultimoacceso || "N/A"}</td>
                  <td>
                    <div className="flex gap-2">
                      <button onClick={() => handleEdit(usuario.idcredenciales)} className="btn btn-info btn-xs">
                        <FontAwesomeIcon icon={faPencilAlt} />
                      </button>
                      <button onClick={() => handleDelete(usuario.idcredenciales)} className="btn btn-error btn-xs">
                        <FontAwesomeIcon icon={faTrashAlt} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="5" className="text-center text-gray-500">
                  No hay usuarios disponibles
                </td>
              </tr>
            )}
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
                ? "bg-blue-500 text-white"
                : "bg-gray-200 hover:bg-gray-300"
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

export default Users;
