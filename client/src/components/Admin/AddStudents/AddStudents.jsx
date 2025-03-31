import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import Table from './Table';

const AddStudents = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
       <Table />
    </main>
    <Footer />
  </div>
);
};


export default AddStudents;