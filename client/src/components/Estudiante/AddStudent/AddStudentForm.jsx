import { useState } from "react";
import Swal from "sweetalert2";
import studentService from "../../services/studentService";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUser, faCheckCircle, faTimesCircle, faPlus } from "@fortawesome/free-solid-svg-icons";

const AddStudentForm = () => {
  const [formData, setFormData] = useState({
    nombreEstudiante: "",
    apellidoEstudiante: "",
    estado: "",
  });

  const [errors, setErrors] = useState({});
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    setErrors((prev) => ({ ...prev, [name]: "" }));
  };

  const validateForm = () => {
    const newErrors = {};
    if (!formData.nombreEstudiante.trim()) newErrors.nombreEstudiante = "Campo requerido";
    if (!formData.apellidoEstudiante.trim()) newErrors.apellidoEstudiante = "Campo requerido";
    if (!formData.estado) newErrors.estado = "Seleccione una opción";
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validateForm() || isSubmitting) return;

    setIsSubmitting(true);

    try {
      const result = await studentService.agregarEstudiante(formData);

      Swal.fire({
        icon: "success",
        title: "¡Éxito!",
        text: result.message,
        confirmButtonColor: "#6B46C1",
        timer: 1500,
      });

      setFormData({ nombreEstudiante: "", apellidoEstudiante: "", estado: "" });
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: error.message,
        confirmButtonColor: "#6B46C1",
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="flex items-center justify-center bg-gray-100 py-10">
      <div className="max-w-md w-full bg-white rounded-lg shadow-lg border border-gray-200">
        <header className="p-4 border-b border-gray-100 text-center">
          <h1 className="text-lg font-semibold text-gray-800">Registro de Estudiantes</h1>
          <p className="text-gray-500 text-sm">Ingrese la información requerida</p>
        </header>

        <form onSubmit={handleSubmit} className="p-3 space-y-3">
          {/* Nombre del Estudiante */}
          <div>
            <label className="block text-sm font-medium text-gray-700">Nombre del Estudiante</label>
            <div className="relative mt-1">
              <FontAwesomeIcon icon={faUser} className="absolute left-3 top-3 text-gray-400" />
              <input
                type="text"
                name="nombreEstudiante"
                value={formData.nombreEstudiante}
                onChange={handleChange}
                placeholder="Ejemplo: María"
                className={`w-full pl-10 pr-3 py-2 border rounded-md bg-gray-100 text-gray-700 focus:outline-none transition-colors ${
                  errors.nombreEstudiante ? "border-red-500 focus:ring-red-200" : "border-gray-300 focus:border-purple-500 focus:ring-purple-200"
                }`}
              />
            </div>
            {errors.nombreEstudiante && <p className="text-red-500 text-xs mt-1">{errors.nombreEstudiante}</p>}
          </div>

          {/* Apellido del Estudiante */}
          <div>
            <label className="block text-sm font-medium text-gray-700">Apellido del Estudiante</label>
            <div className="relative mt-1">
              <FontAwesomeIcon icon={faUser} className="absolute left-3 top-3 text-gray-400" />
              <input
                type="text"
                name="apellidoEstudiante"
                value={formData.apellidoEstudiante}
                onChange={handleChange}
                placeholder="Ejemplo: González"
                className={`w-full pl-10 pr-3 py-2 border rounded-md bg-gray-100 text-gray-700 focus:outline-none transition-colors ${
                  errors.apellidoEstudiante ? "border-red-500 focus:ring-red-200" : "border-gray-300 focus:border-purple-500 focus:ring-purple-200"
                }`}
              />
            </div>
            {errors.apellidoEstudiante && <p className="text-red-500 text-xs mt-1">{errors.apellidoEstudiante}</p>}
          </div>

          {/* Estado de Asistencia */}
          <div>
            <label className="block text-sm font-medium text-gray-700">Estado de Asistencia</label>
            <div className="relative mt-1">
              <select
                name="estado"
                value={formData.estado}
                onChange={handleChange}
                className={`w-full pl-3 pr-10 py-2 border rounded-md bg-gray-100 text-gray-700 focus:outline-none transition-colors appearance-none ${
                  errors.estado ? "border-red-500 focus:ring-red-200" : "border-gray-300 focus:border-purple-500 focus:ring-purple-200"
                }`}
              >
                <option value="">Seleccione una opción</option>
                <option value="si">Asistió</option>
                <option value="no">No asistió</option>
              </select>
              <FontAwesomeIcon
                icon={formData.estado === "si" ? faCheckCircle : formData.estado === "no" ? faTimesCircle : null}
                className={`absolute right-3 top-3 text-gray-400 ${formData.estado === "si" ? "text-green-500" : formData.estado === "no" ? "text-red-500" : ""}`}
              />
            </div>
            {errors.estado && <p className="text-red-500 text-xs mt-1">{errors.estado}</p>}
          </div>

          {/* Botón de Envío */}
          <button
            type="submit"
            disabled={isSubmitting}
            className={`w-full py-2 px-4 text-white font-medium rounded-md flex items-center justify-center gap-2 transition-colors ${
              isSubmitting ? "bg-purple-400 cursor-not-allowed" : "bg-purple-600 hover:bg-purple-700 focus:ring-2 focus:ring-purple-300"
            } focus:outline-none`}
          >
            <FontAwesomeIcon icon={faPlus} />
            {isSubmitting ? "Registrando..." : "Agregar Estudiante"}
          </button>
        </form>
      </div>
    </div>
  );
};

export default AddStudentForm;
