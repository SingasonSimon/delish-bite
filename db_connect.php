<?php
// --- Database Connection ---

$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "delishbite_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection for errors
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}