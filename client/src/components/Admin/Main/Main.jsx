import Footer from '../../Footer';
import Navbar from './Nabvar';
import ChartView from './ChartView';

const Main = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <ChartView/>
    </main>
    <Footer />
  </div>
);
};


export default Main;