<?php
include("../index.php"); //include the database connection
// header("Access-Control-Allow-Origin: *");
// header("Content-type: application/json");
// header('Access-Control-Allow-Method: GET');

$resuestMethod =$_SERVER["REQUEST_METHOD"];

if($resuestMethod == "GET"){

    $studentList = getStudentList();
    echo $studentList;
}
else{
    //print error message
    $data = [
        'status' => 405,
        'message'=> $resuestMethod .'Invalid request method',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}

function gettudentList(){
    global $conn ;

    $query = "SELECT * FROM students";
    //execute this query
}

?>