const StudentDetails = ({ student }) => {

    const handleClick = async (studentID) =>{
        const response = await fetch('http://localhost:9000/api/delete.php?studentID=${studentID}',{
            method: 'DELETE'
        })
        const json = await response.json()

        if(response.ok){
            console.log(json);
        }
    }
    
    return (  
        <div className="student-details">
            <h4>{ student.FirstName  } { student.LastName }</h4>
            <p>Student ID : { student.studentID } </p>
            <p><b>Date of Birth: </b> { student.DateofBirth }</p>
            <p><b>Email: </b> { student.Email }</p>
            <p><b>Address: </b> { student.Address }</p>
            <span onClick={handleClick}>Delete</span>
            {/* <button onClick={()=>console.log(student)}>View Full Details</button> */}
        </div>
    );
}
 
export default StudentDetails;