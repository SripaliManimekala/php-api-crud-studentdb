import { useEffect, useState } from "react";


const Home = () => {
    const [students, setStudents] = useState(null)

    useEffect(() =>{
        const fetchStudents= async () => {
            const response = await fetch('http://localhost:9000/api/getall.php')//store data from this endpoint in the response
            const json =await response.json()

            if(response.ok){
                setStudents(json.data)
            }

        }

        fetchStudents()
    },[])

    return ( 
        <div className="home">
            <div className="students">
                {students && students.map((student) => (
                    <p key={student.studentID}>{student.FirstName}</p>
                ))}
            </div>
        </div>
     );
}
 
export default Home;