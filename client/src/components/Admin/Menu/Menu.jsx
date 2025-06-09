import Footer from '../../Footer';
import Navbar from '../Main/Nabvar';
import MenuSection from './MenuSection';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1 flex flex-col items-center px-4 sm:px-6 lg:px-8 w-full">
        <div className="w-full max-w-4xl">
          <MenuSection />
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default Main;