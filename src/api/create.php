<?php
//  
include("../index.php"); //include the database connection
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
header('Access-Control-Allow-Method: POST');


$requestMethod =$_SERVER["REQUEST_METHOD"];

if($requestMethod == "POST"){
    
    $input_data= json_decode(file_get_contents("php://input"), true); //for any data
    echo $input_data['name'];  
    
    
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

?>