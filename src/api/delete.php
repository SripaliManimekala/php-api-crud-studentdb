<?php
error_reporting(0); 
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
        'message'=> $requestMethod .' Invalid request method',
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

    // Check if the student ID exists in the database
    $checkQuery = "SELECT COUNT(*) as count FROM students WHERE studentID = '$studentID'";
    $checkResult = mysqli_query($conn, $checkQuery);
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['count'] == 0) {
        // If the student ID doesn't exist, return an error response
        $errorData = [
            'status' => 404, // Not Found
            'message' => 'No student record found to delete',
        ];
        header("HTTP/1.0 404 Not Found");
        return json_encode($errorData);
    }

    $query = "DELETE FROM students WHERE studentID='$studentID' LIMIT 1";
    //execute this query
    $query_run = mysqli_query($conn, $query);

    //check if record exist or not
    if($query_run){

        $errorData = [
            'status' => 200, //deleted
            'message' => 'Student record deleted successfully',
        ];
        header("HTTP/1.0 200 DELETED");
        return json_encode($errorData);

    }
    else{//no student records found
            $data = [
                'status' => 500,
                'message'=> 'Internal server error',
            ];
            header("HTTP/1.0 500 Internal server error");
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