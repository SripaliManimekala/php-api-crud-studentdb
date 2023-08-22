<?php
error_reporting(0); 
include("../index.php"); //include the database connection
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
header('Access-Control-Allow-Method: PUT');


$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "PUT") {
    
    $input_data = json_decode(file_get_contents("php://input"), true); 
    $updated_student = updateStudent($input_data, $_GET); 
    echo $updated_student;
             
} else {
    //print error message
    $data = [
        'status' => 405,
        'message' => $requestMethod .' Invalid request method',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}

function updateStudent($student, $student_params){
    global $conn ;

    if (!isset($student_params['studentID'])){
        return inputValidationError('Student ID parameter not Found!');
    } elseif ($student_params['studentID'] == null) {
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
            'message' => 'ID not found in database',
        ];
        header("HTTP/1.0 404 Not Found");
        return json_encode($errorData);
    } else {
        // Validate empty fields
        foreach ($student as $field => $value) {
            if (empty(trim($value))) {
                return inputValidationError('Empty fields Detected');
            }
        }

        // Update the student record
        $query = "UPDATE students SET ";
        $fields = [];

        foreach ($student as $field => $value) {
            $sanitizedValue = mysqli_real_escape_string($conn, $value);
            $fields[] = "$field = '$sanitizedValue'";
        }

        $query .= implode(", ", $fields);
        $query .= " WHERE studentID = '$studentID' LIMIT 1";

        $query_run = mysqli_query($conn, $query);

        if ($query_run) {
            // Fetch and return the updated student record
            $getUpdatedRecordQuery = "SELECT * FROM students WHERE studentID = '$studentID'";
            $updatedRecordResult = mysqli_query($conn, $getUpdatedRecordQuery);
            $updatedRecord = mysqli_fetch_assoc($updatedRecordResult);
            
            $data = [
                'status' => 200,
                'message' => 'Student updated successfully',
                'updated_student' => $updatedRecord,
            ];
            header("HTTP/1.0 200 Success");
            return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
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
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
}
?>
