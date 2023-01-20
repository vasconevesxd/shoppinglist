<?php 
    error_reporting(0);
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "shopping_list";
    

    // Create connection
    $conn = new mysqli($servername, $username, $password,$db);

    // Check connection
    if ($conn->connect_error) {
    echo "<span class='alert alert-danger' role='alert' style='z-index:999;position:absolute;top: 10%;'>
        A simple danger alert with <a href='#' class='alert-link'>". $conn->connect_error."</a>
    </span>";
    header('Location: error.php');
    die();
    }

    return $conn
?>