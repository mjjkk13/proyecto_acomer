const API_URL = import.meta.env.VITE_API_URL;

/**
 * Obtiene los cursos disponibles desde el backend.
 */
export const getCourses = async () => {
    try {
        const response = await fetch(`${API_URL}/cargarCursos.php`);
        const data = await response.json();
        
        if (Array.isArray(data)) {
            console.warn("⚠️ El backend devolvió un array en lugar de un objeto esperado.");
            return { success: true, data }; // Ajustamos la estructura manualmente
        }

        return data;
    } catch (error) {
        console.error("❌ Error obteniendo cursos:", error);
        return { success: false, data: [] };
    }
};



/**
 * Sube el archivo Excel con los estudiantes y asigna un curso.
 * @param {File} file - Archivo Excel con los estudiantes.
 * @param {number} courseId - ID del curso seleccionado.
 */
export const uploadStudents = async (file, courseId) => {
    const formData = new FormData();
    formData.append("file", file);
    formData.append("courseId", courseId);

    try {
        const response = await fetch(`${API_URL}/añadirAlumno_excel.php`, {
            method: "POST",
            body: formData,
        });

        const textResponse = await response.text(); // Obtener respuesta como texto
        console.log("Raw Response:", textResponse); // Mostrar la respuesta en la consola

        // Intentar convertirla en JSON
        const result = JSON.parse(textResponse);
        return result;
    } catch (error) {
        console.error("Error subiendo estudiantes:", error);
        return { error: "Hubo un problema con la subida del archivo." };
    }
};
