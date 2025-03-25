import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import Users from './Users';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <Users/>
    </main>
    <Footer />
  </div>
);
};


export default Main;