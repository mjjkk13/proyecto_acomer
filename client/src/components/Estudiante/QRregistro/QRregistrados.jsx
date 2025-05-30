import Footer from '../../Footer';
import Navbar from '../Main/Navbar';
import QRScannerlist from './QRScannerlist';

const QRregistrados = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
      <Navbar />
      <main className="flex-1 items-center">
        <div className="my-4"></div>
        <QRScannerlist />
      </main>
      <Footer />
    </div>
  );
};


export default QRregistrados;