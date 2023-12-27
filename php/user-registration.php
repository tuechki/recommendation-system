<?php
include 'db-connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $interests = $_POST["interests"];

    $sql = "INSERT INTO users (email, password, interests) VALUES ('$email', '$password', '$interests')";

    if ($conn->query($sql) === TRUE) {
        echo "User inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>