import LoginNavbar from './LoginNavbar';
import FormLogin from './FormLogin';
import Footer from '../Footer';
import '../../styles/Login.css'

const Login = () => {
return (
    <>
        <LoginNavbar />
        <div className="flex justify-center items-center p-8">
            <div className="w-full max-w-sm">
                <div className="card bg-base-100 shadow-xl">
                    <div className="card-body p-6">
                        <h5 className="text-center text-2xl font-semibold mb-6">Iniciar Sesión</h5>
                            <FormLogin />
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </>
);
};

export default Login;