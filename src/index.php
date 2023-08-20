<?php


$con = new mysqli('mysql_db','root','root','studentdb');
// if($con->connect_error){

if ($con){
    echo "connected to the database";
}