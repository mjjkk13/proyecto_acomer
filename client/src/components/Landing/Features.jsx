import { useNavigate } from 'react-router-dom';
import '../../styles/landing.css'
const Features = () => {
    const navigate = useNavigate();

    return (
        <section className="features py-16">
            <div className="container mx-auto">
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center">
                    <div
                        className="feature-box bg-gray-200 p-8 rounded-lg shadow-lg hover:scale-110 transition-transform cursor-pointer"
                        onClick={() => navigate('/login')}
                    >
                        <h3 className="text-2xl font-bold mb-4 text-black">Estudiante</h3>
                        <p className="text-black">Accede al lector QR y otras funciones de nuestro sistema.</p>
                    </div>
                    <div
                        className="feature-box bg-gray-200 p-8 rounded-lg shadow-lg hover:scale-110 transition-transform cursor-pointer"
                        onClick={() => navigate('/login')}
                    >
                        <h3 className="text-2xl font-bold mb-4 text-black">Docente</h3>
                        <p className="text-black">Ingresa a las listas de estudiantes, genera tu QR y observa el menú del día.</p>
                    </div>
                    <div
                        className="feature-box bg-gray-200 p-8 rounded-lg shadow-lg hover:scale-110 transition-transform cursor-pointer"
                        onClick={() => navigate('/login')}
                    >
                        <h3 className="text-2xl font-bold mb-4 text-black">Administrador</h3>
                        <p className="text-black">Gestiona usuarios, contenido y más desde nuestra plataforma.</p>
                    </div>
                </div>
            </div>
        </section>
    );
};

export default Features;
