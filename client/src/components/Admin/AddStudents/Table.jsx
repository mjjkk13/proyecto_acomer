import { useState, useEffect, useRef } from "react";
import { getCourses, uploadStudents } from "../../services/addStudent";
import Swal from "sweetalert2";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUpload, faFileExcel, faCheck, faTimes } from "@fortawesome/free-solid-svg-icons";

const UploadStudents = () => {
    const [courses, setCourses] = useState([]);
    const [selectedCourse, setSelectedCourse] = useState("");
    const [file, setFile] = useState(null);
    const fileInputRef = useRef(null); // Referencia para el input file

    useEffect(() => {
        getCourses().then((response) => {
            if (response.success && Array.isArray(response.data)) {
                setCourses(response.data);
            } else if (Array.isArray(response)) { 
                setCourses(response); 
            } else {
                setCourses([]);
            }
        }).catch(() => {
            setCourses([]);
        });
    }, []);

    const handleFileChange = (e) => setFile(e.target.files[0]);
    const handleCourseChange = (e) => setSelectedCourse(e.target.value);

    // Función para quitar archivo seleccionado
    const handleRemoveFile = () => {
        setFile(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = null; // Limpia el input visualmente
        }
    };

    const handleUpload = async () => {
        if (!file || !selectedCourse) {
            Swal.fire("Error", "Selecciona un curso y un archivo.", "error");
            return;
        }

        try {
            const result = await uploadStudents(file, selectedCourse);
            if (result.success) {
                Swal.fire("Éxito", result.message || "Archivo subido correctamente", "success");
                setFile(null);
                setSelectedCourse("");
                if (fileInputRef.current) {
                    fileInputRef.current.value = null;
                }
            } else {
                throw new Error(result.error || "Hubo un problema con la subida del archivo.");
            }
        } catch (error) {
            Swal.fire("Error", error.message, "error");
        }
    };

    return (
        <div className="max-w-md mx-auto p-6 bg-white shadow-xl rounded-lg mt-20">
            <h2 className="text-xl font-bold text-center mb-4 flex items-center justify-center gap-2">
                <FontAwesomeIcon icon={faUpload} /> Subir Listado de Alumnos
            </h2>

            {/* Selector de Curso */}
            <div className="mb-4">
                <label className="block text-sm font-semibold">Selecciona un curso:</label>
                <select 
                    className="select select-bordered w-full bg-white text-gray-900 focus:outline-none focus:ring"
                    value={selectedCourse}
                    onChange={handleCourseChange}
                >
                    <option value="">-- Seleccionar --</option>
                    {courses.length > 0 ? (
                        courses.map(course => (
                            <option key={course.id} value={course.id}>
                                {course.nombre}
                            </option>
                        ))
                    ) : (
                        <option disabled>Cargando cursos...</option>
                    )}
                </select>
            </div>

            {/* Input de Archivo */}
            <div className="mb-4 relative">
                <label className="block text-sm font-semibold flex items-center gap-2">
                    <FontAwesomeIcon icon={faFileExcel} className="text-green-500" /> Selecciona un archivo Excel:
                </label>
                <input 
                    type="file" 
                    accept=".xlsx, .xls"
                    className="file-input file-input-bordered w-full bg-white text-gray-900 focus:outline-none focus:ring mt-2"
                    onChange={handleFileChange}
                    ref={fileInputRef}
                />
                {file && (
                    <button
                        type="button"
                        onClick={handleRemoveFile}
                        className="absolute top-8 right-2 text-red-500 hover:text-red-700"
                        aria-label="Quitar archivo"
                    >
                        <FontAwesomeIcon icon={faTimes} />
                    </button>
                )}
            </div>

            {/* Botón de Subida */}
            <button 
                className="btn btn-primary w-full flex items-center gap-2"
                onClick={handleUpload}
            >
                <FontAwesomeIcon icon={faCheck} /> Subir Archivo
            </button>
        </div>
    );
};

export default UploadStudents;
