import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import Table from './Table';

const AddStudents = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1 flex flex-col items-center px-2 sm:px-4 md:px-6 py-4 w-full">
        <div className="w-full max-w-4xl bg-white rounded-lg shadow-md p-2 sm:p-4 md:p-6">
          <h1 className="text-xl sm:text-2xl font-semibold mb-4 text-center">Agregar Estudiantes</h1>
          <Table />
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default AddStudents;