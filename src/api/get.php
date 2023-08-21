<?php
include("../index.php"); //include the database connection
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
header('Access-Control-Allow-Method: GET');


$resuestMethod =$_SERVER["REQUEST_METHOD"];

if($resuestMethod == "GET"){

    $student = getStudent();
    echo $student;
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

function getStudent(){
    global $conn ;

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