<?php
error_reporting(0); 
include("../index.php"); //include the database connection
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
header('Access-Control-Allow-Method: GET');


$requestMethod =$_SERVER["REQUEST_METHOD"];

if($requestMethod == "GET"){

    $student = getStudent();
    echo $student;
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

function getStudent(){
    global $conn ;

    // Check if the student ID exists in the database
    $checkQuery = "SELECT COUNT(*) as count FROM students WHERE studentID = '$studentID'";
    $checkResult = mysqli_query($conn, $checkQuery);
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['count'] == 0) {
        // If the student ID doesn't exist, return an error response
        $errorData = [
            'status' => 404, // Not Found
            'message' => 'ID not found',
        ];
        header("HTTP/1.0 404 Not Found");
        return json_encode($errorData);
    }

    $query = "SELECT * FROM students WHERE studentID=".$_GET['studentID'];
    //execute this query
    $query_run = mysqli_query($conn, $query);

        //check if record exist or not
    if($query_run){

        if(mysqli_num_rows($query_run) > 0){

            $response  = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message'=> 'Student fetched successsfully',
                'data' => $response
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data);

        }else{//no student records found
            $data = [
                'status' => 404,
                'message'=> 'No student found for given ID',
            ];
            header("HTTP/1.0 404 No student found for given ID");
            return json_encode($data);
        }
    }
    else{
        //print error message
        $data = [
            'status' => 500,
            'message'=> 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }

}
?>