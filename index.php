<?php

$func = $_POST['func'];
if ($func == 'retrieve')
    retreive();
else
    insert();


function insert()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "anExtraRep";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn)
        die("connection failed:" . mysqli_connect_error());
    echo "successful connection<br>";

    // $sql ="CREATE DATABASE AnExtraRep";
    // if(mysqli_query($conn,$sql))
    // echo "Database Created";
    // else
    // echo"Error";

    // $sql2 = "CREATE TABLE users (
    //     name VARCHAR(40),
    //     age INT,
    //     weight FLOAT,
    //     email VARCHAR(30),
    //     health_report BLOB
    //   )";

    // if(mysqli_query($conn,$sql2))
    // echo "Table Created";
    // else
    // echo"Error";


    $name = $_POST['name'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $email = $_POST['email'];

    // Check if a file was uploaded
    if ($_FILES['health_report']['error'] === UPLOAD_ERR_OK) {
        $healthReportFile = $_FILES['health_report']['tmp_name'];
        $healthReportContent = file_get_contents($healthReportFile);
        $healthReportContent = mysqli_real_escape_string($conn, $healthReportContent); // Escaping special characters

        $query = "INSERT INTO users (name, age, weight, email, health_report) VALUES ('$name', '$age', '$weight', '$email', '$healthReportContent')";

        if (mysqli_query($conn, $query)) {
            header("Location: index.html");
        exit();
        } else {
            echo "Error inserting record: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading file: " . $_FILES['health_report']['error'];

    }

    mysqli_close($conn);
}


function retreive()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "anExtraRep";

    $conn = mysqli_connect($servername, $username, $password, $database);

    $email = $_POST['email'];

    $query = "SELECT health_report FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $healthReportContent = $row['health_report'];

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename='health_report.pdf'");

        echo $healthReportContent;
    } else {
        echo "No health report found for the given email.";
    }
    mysqli_close($conn);
}


?>