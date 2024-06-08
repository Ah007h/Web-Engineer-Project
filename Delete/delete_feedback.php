<?php
session_start();

// Check if the user is logged in and has the "staff" role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}

// Check if the feedback ID is provided
if (!isset($_POST['feedbackId'])) {
    echo json_encode(array('success' => false, 'message' => 'Feedback ID not provided.'));
    exit();
}

$feedbackId = $_POST['feedbackId'];

// Establish the database connection
$hostname = 'localhost'; // Replace with your database hostname
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$database = 'library'; // Replace with your database name

$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    echo json_encode(array('success' => false, 'message' => 'Failed to connect to the database: ' . mysqli_connect_error()));
    exit();
}

// Delete the feedback record from the database
$query = "DELETE FROM feedback WHERE id = '$feedbackId'";
$result = mysqli_query($connection, $query);

// Check if the query executed successfully
if ($result) {
    echo json_encode(array('success' => true, 'message' => 'Feedback deleted successfully.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to delete feedback: ' . mysqli_error($connection)));
}

// Close the database connection
mysqli_close($connection);
?>