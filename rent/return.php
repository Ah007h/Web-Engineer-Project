<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect to the login page if the user is not logged in
  header("Location: login.php");
  exit;
}

// Check if the bookId is provided in the query string
if (!isset($_GET['bookId'])) {
  // Redirect to the rental list page if the bookId is not provided
  header("Location: rentbooks.php");
  exit;
}

// Get the bookId from the query string
$bookId = $_GET['bookId'];

// Establish the database connection
$hostname = 'localhost'; // Replace with your database hostname
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$database = 'library'; // Replace with your database name

$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
  die('Failed to connect to the database: ' . mysqli_connect_error());
}

// Update the rental record to mark the book as returned
$updateQuery = "UPDATE rental SET returned = 'Yes' WHERE id = '$bookId' AND username = '" . $_SESSION['username'] . "'";
$updateResult = mysqli_query($connection, $updateQuery);

if ($updateResult) {
  // Return successful, redirect to the rental list page
  $_SESSION['successMessage'] = "Book returned successfully!";
} else {
  // Return failed, set error message in session variable
  $_SESSION['errorMessage'] = "Failed to return the book. Please try again.";
}

// Close the database connection
mysqli_close($connection);

// Redirect to the rental list page
header("Location: rentbooks.php");
exit;
?>