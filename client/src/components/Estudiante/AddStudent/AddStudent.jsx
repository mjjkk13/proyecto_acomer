import Navbar from "./Nabvar";
import AddStudentForm from "./AddStudentForm";
import Footer from "../../Footer";

const AddStudent = () =>{
    return(
    <div className="bg-gray-100 min-h-screen">
      <Navbar />
      <AddStudentForm />
      <Footer />
    </div>
    )
}

export default AddStudent;