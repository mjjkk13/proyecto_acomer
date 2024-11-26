import Footer from '../Footer';
import Navbar from './Navbar';
import '../../styles/landing.css';
import Hero from './Hero';
import Features from './Features';

const Landing = () => {
  return (
    <div>
      <Navbar /> 
      <Hero />
      <Features />
      <Footer />
    </div>
  );
};

export default Landing;