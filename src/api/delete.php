<?php
include("../index.php"); //include the database connection
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
header('Access-Control-Allow-Method: DELETE');


$requestMethod =$_SERVER["REQUEST_METHOD"];

if($requestMethod == "DELETE"){

    $deleted_student = deleteStudent($_GET);
    echo $deleted_student;
}
else{
    //print error message
    $data = [
        'status' => 405,
        'message'=> $requestMethod .'Invalid request method',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}

function deleteStudent($student_params){
    global $conn ;

    if(!isset($student_params['studentID'])){
        return inputValidationError('Studet ID not Found!');
    }
    elseif($student_params['studentID']==null){
        return inputValidationError('Enter the student ID value'); 
    }

    $studentID = mysqli_real_escape_string($conn, $student_params['studentID']);

    $query = "DELETE FROM students WHERE studentID='$studentID' LIMIT 1";
    //execute this query
    $query_run = mysqli_query($conn, $query);

    //check if record exist or not
    if($query_run){

            $data = [
                'status' => 204,
                'message'=> 'Student deleted successsfully',
                //'data' => $response
            ];
            header("HTTP/1.0 204 Deleted");
            return json_encode($data);

    }
    else{//no student records found
            $data = [
                'status' => 404,
                'message'=> 'No student found for given ID',
            ];
            header("HTTP/1.0 404 No student found for given ID");
            return json_encode($data);
    }
}

function inputValidationError($message){
    $data = [
        'status' => 422,
        'message'=> $message,
    ];
    header("HTTP/1.0 422 unprocessable entity");
    echo json_encode($data);
}
?>