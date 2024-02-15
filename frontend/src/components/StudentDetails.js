const StudentDetails = ({ student }) => {
    return (  
        <div className="student-details">
            <h4>{ student.FirstName  } { student.LastName }</h4>
            <p><b>Date of Birth: </b> { student.DateofBirth }</p>
            <p><b>Email: </b> { student.Email }</p>
            <p><b>Address: </b> { student.Address }</p>
            {/* <button onClick={()=>console.log(student)}>View Full Details</button> */}
        </div>
    );
}
 
export default StudentDetails;