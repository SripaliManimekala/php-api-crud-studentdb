<?php
error_reporting(0); 
include("../index.php"); //include the database connection
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
header('Access-Control-Allow-Method: POST');


$requestMethod =$_SERVER["REQUEST_METHOD"];

if($requestMethod == "POST"){
    
    $input_data= json_decode(file_get_contents("php://input"), true); //for any data(raw data format)
    //if raw data is empty check post method
    if(empty($input_data)){
        //echo "not from raw data\n";
        $created_student = createStudent($_POST);
    }
    else{
        $created_student = createStudent($input_data); 
    }
    echo $created_student;
             
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

function createStudent($student){
    global $conn ;

    $studentID = mysqli_real_escape_string($conn, $student['studentID']);
    $FirstName = mysqli_real_escape_string($conn, $student['FirstName']);
    $LastName = mysqli_real_escape_string($conn, $student['LastName']);
    $DateofBirth = mysqli_real_escape_string($conn, $student['DateofBirth']);
    $Address = mysqli_real_escape_string($conn, $student['Address']);
    $Email = mysqli_real_escape_string($conn, $student['Email']);

    //validate
    //check all entries entered
    if(empty(trim($studentID)) || empty(trim($FirstName)) || empty(trim($LastName)) || empty(trim($DateofBirth)) || empty(trim($Address)) || empty(trim($Email))){
        return inputValidationError('Empty field detected');
    }
    else{
        $query = "INSERT INTO students(studentID,FirstName,LastName,DateofBirth,Address,Email) VALUE ('$studentID','$FirstName','$LastName','$DateofBirth','$Address','$Email')";
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
                'status' => 201,
                'message'=> 'Student created successfully',
            ];
            header("HTTP/1.0 201 Created successfully");
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