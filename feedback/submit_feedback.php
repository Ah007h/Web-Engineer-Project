<?php
session_start();

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'library';

// Create a database connection
$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
  die('Failed to connect to the database: ' . mysqli_connect_error());
}

// Check if the feedback form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the form data
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  // Escape special characters in the input
  $name = mysqli_real_escape_string($connection, $name);
  $email = mysqli_real_escape_string($connection, $email);
  $message = mysqli_real_escape_string($connection, $message);

  // Insert the feedback into the database
  $query = "INSERT INTO feedback (name, email, message) VALUES ('$name', '$email', '$message')";
  $result = mysqli_query($connection, $query);

  if ($result) {
    // Feedback submitted successfully
    echo '<script>alert("Thank you for your feedback!");</script>';
  } else {
    // Error occurred while submitting feedback
    echo '<script>alert("Failed to submit feedback. Please try again later.");</script>';
  }
}

// Close the database connection
mysqli_close($connection);
?>
