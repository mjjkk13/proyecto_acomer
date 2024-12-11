import { useState } from "react";
import Swal from "sweetalert2";

const AddStudentForm = () => {
  const [formData, setFormData] = useState({
    nombreEstudiante: "",
    apellidoEstudiante: "",
    estado: "",
  });

  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
    setErrors({ ...errors, [name]: "" }); // Elimina el error cuando el usuario comienza a escribir
  };

  const validateForm = () => {
    const newErrors = {};
    if (!formData.nombreEstudiante.trim()) {
      newErrors.nombreEstudiante = "El nombre es obligatorio.";
    }
    if (!formData.apellidoEstudiante.trim()) {
      newErrors.apellidoEstudiante = "El apellido es obligatorio.";
    }
    if (!formData.estado) {
      newErrors.estado = "Selecciona un estado.";
    }
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0; // Devuelve true si no hay errores
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validateForm()) return;

    try {
      const response = await fetch(
        "http://localhost/proyecto_acomer/server/php/AgregarEstudiante.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams(formData),
        }
      );

      const result = await response.json();

      if (result.status === "success") {
        Swal.fire("Éxito", result.message, "success");
        setFormData({ nombreEstudiante: "", apellidoEstudiante: "", estado: "" });
        setErrors({});
      } else {
        Swal.fire("Error", result.message, "error");
      }
    } catch {
      Swal.fire("Error", "Hubo un problema al enviar los datos.", "error");
    }
  };

  return (
    <div className="container mx-auto mt-10">
      <div className="card shadow-lg max-w-sm mx-auto">
        <div className="card-header text-white text-center p-4">
          <h4 className="text-lg font-bold">Agregar Estudiante</h4>
        </div>
        <div className="card-body p-6">
          <form onSubmit={handleSubmit} className="space-y-3 relative z-1">
            <div className="form-control">
              <label className="label" htmlFor="nombreEstudiante">
                <span className="label-text">
                  <i className="fas fa-user"></i> Nombre del Estudiante
                </span>
              </label>
              <input
                type="text"
                id="nombreEstudiante"
                name="nombreEstudiante"
                value={formData.nombreEstudiante}
                onChange={handleChange}
                placeholder="Escribe el nombre del estudiante"
                className={`input input-bordered w-full text-sm ${
                  errors.nombreEstudiante ? "border-red-500" : ""
                }`}
              />
              {errors.nombreEstudiante && (
                <span className="text-red-500 text-xs">{errors.nombreEstudiante}</span>
              )}
            </div>
            <div className="form-control">
              <label className="label" htmlFor="apellidoEstudiante">
                <span className="label-text">
                  <i className="fas fa-user"></i> Apellido del Estudiante
                </span>
              </label>
              <input
                type="text"
                id="apellidoEstudiante"
                name="apellidoEstudiante"
                value={formData.apellidoEstudiante}
                onChange={handleChange}
                placeholder="Escribe el apellido del estudiante"
                className={`input input-bordered w-full text-sm ${
                  errors.apellidoEstudiante ? "border-red-500" : ""
                }`}
              />
              {errors.apellidoEstudiante && (
                <span className="text-red-500 text-xs">{errors.apellidoEstudiante}</span>
              )}
            </div>
            <div className="form-control">
              <label className="label" htmlFor="estado">
                <span className="label-text">
                  <i className="fas fa-check-circle"></i> Asistió
                </span>
              </label>
              <select
                id="estado"
                name="estado"
                value={formData.estado}
                onChange={handleChange}
                className={`select select-bordered w-full text-sm ${
                  errors.estado ? "border-red-500" : ""
                }`}
              >
                <option value="">Selecciona una opción</option>
                <option value="si">Sí</option>
                <option value="no">No</option>
              </select>
              {errors.estado && (
                <span className="text-red-500 text-xs">{errors.estado}</span>
              )}
            </div>
            <div className="form-control mt-4">
              <button type="submit" className="btn btn-primary w-full text-sm">
                Agregar Estudiante
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default AddStudentForm;
