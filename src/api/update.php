<?php
error_reporting(0); 
include("../index.php"); //include the database connection
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
header('Access-Control-Allow-Method: PUT');


$requestMethod =$_SERVER["REQUEST_METHOD"];

if($requestMethod == "PUT"){
    
    $input_data= json_decode(file_get_contents("php://input"), true); 
    $updated_student = updateStudent($input_data, $_GET); 
    echo $updated_student;
             
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

function updateStudent($student, $student_params){
    global $conn ;

    if(!isset($student_params['studentID'])){
        return inputValidationError('Studet ID not Found!');
    }
    elseif($student_params['studentID']==null){
        return inputValidationError('Enter the student ID'); 
    }

    $studentID = mysqli_real_escape_string($conn, $student_params['studentID']);

    $FirstName = mysqli_real_escape_string($conn, $student['FirstName']);
    $LastName = mysqli_real_escape_string($conn, $student['LastName']);
    $DateofBirth = mysqli_real_escape_string($conn, $student['DateofBirth']);
    $Address = mysqli_real_escape_string($conn, $student['Address']);
    $Email = mysqli_real_escape_string($conn, $student['Email']);

    //validate
    //check all entries entered
    if(empty(trim($FirstName))){
        return inputValidationError('Enter first name field');
    }
    elseif(empty(trim($LastName))){
        return inputValidationError('Enter last name field');
    }
    elseif(empty(trim($DateofBirth))){
        return inputValidationError('Enter date of birth field');
    }
    elseif(empty(trim($Address))){
        return inputValidationError('Enter address field');
    }
    elseif(empty(trim($Email))){
        return inputValidationError('Enter email field');
    }
    else{
        $query = "UPDATE students SET FirstName='$FirstName', LastName ='$LastName', DateofBirth ='$DateofBirth',Address ='$Address',Email =  '$Email' WHERE studentID = '$studentID' LIMIT 1 ";
        $query_run = mysqli_query($conn, $query);
        if($query_run){
            
            $responseData= array(
                "student ID" =>$studentID,
                "first Name" =>$FirstName ,
                "last Name" => $LastName,
                "date OfBirth" => $DateofBirth,
                "address" =>$Address,
                "email" =>$Email
            );
            echo json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            echo "\n";
            $data = [
                'status' => 200,
                'message'=> 'Student updated successfully',
            ];
            header("HTTP/1.0 200 updated successfully");
            return json_encode($data);
        }
        else{
            $data = [
                'status' => 500,
                'message'=> 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);  
        }
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