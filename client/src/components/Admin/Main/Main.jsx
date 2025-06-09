import Footer from '../../Footer';
import Navbar from './Nabvar';
import ChartView from './ChartView';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1 flex flex-col items-center px-2 sm:px-6 md:px-12 py-4 w-full">
        <div className="w-full max-w-4xl">
          <ChartView />
        </div>
      </main>
      <Footer />
    </div>
  );
};

export default Main;