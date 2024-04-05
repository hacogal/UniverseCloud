<?php

$servername = "localhost";
$username = "root";
$password = "Hacogal241293.";
$database="A2";

// Create connection
$conn = new mysqli($servername,$username, $password,$database);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

?>