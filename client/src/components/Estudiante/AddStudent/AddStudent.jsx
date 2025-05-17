import Navbar from "../Main/Navbar";
import AddStudentForm from "./AddStudentForm";
import Footer from "../../Footer";

const AddStudent = () => {
  return (
    <div className="min-h-screen flex flex-col bg-gray-100">
    <Navbar />
    <main className="flex-1 items-center">
      <AddStudentForm/>
    </main>
    <Footer />
  </div>
);
};


export default AddStudent;