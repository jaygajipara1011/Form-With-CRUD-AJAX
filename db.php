<?php
$host = "localhost";   // Database host (usually localhost)
$username = "root";    // Database username (default for XAMPP is root)
$password = "";        // Database password (default for XAMPP is empty)
$database = "task";    // Database name (your database name)

// Create a connection using MySQLi
$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if ($conn) {
    // echo "OK"; // If connection is successful, print OK
} else {
    die("Connection failed: " . mysqli_connect_error()); // If connection fails, show the error
}
?>





<!-- $host = "sql311.infinityfree.com";   // Database host (usually localhost)
$username = "if0_37055809";    // Database username (default for XAMPP is root)
$password = "AjYUH29f4NAeD";        // Database password (default for XAMPP is empty)
$database = "if0_37055809_phpcrud";    // Database name (your database name)

// Create a connection using MySQLi
$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection 
if ($conn) {
    // echo "OK"; // If connection is successful, print OK
} else {
    die("Connection failed: " . mysqli_connect_error()); // If connection fails, show the error
} -->

