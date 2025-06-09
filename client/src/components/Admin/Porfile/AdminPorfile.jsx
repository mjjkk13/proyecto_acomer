import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import Porfile from './Porfile';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1 flex justify-center items-start w-full px-2 sm:px-4 md:px-8">
        <div className="w-full max-w-4xl mt-6">
          <Porfile />
        </div>
      </main>
      <Footer />
    </div>
  );
};


export default Main;