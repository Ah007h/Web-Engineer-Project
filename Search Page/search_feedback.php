<?php
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

// Retrieve the search input from the request
$searchInput = $_POST['searchInput'];

// Prepare the SQL query with a WHERE clause for searching
$query = "SELECT id, name, email, message, created_at FROM feedback WHERE id LIKE '%$searchInput%' OR name LIKE '%$searchInput%' OR email LIKE '%$searchInput%'";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query executed successfully
if ($result) {
    // Initialize an empty array to store the feedback data
    $feedbacks = [];

    // Fetch the feedback data and add it to the array
    while ($feedback = mysqli_fetch_assoc($result)) {
        $feedbacks[] = $feedback;
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    echo 'Error executing the query: ' . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);

// Send the feedback data as a JSON response
echo json_encode($feedbacks);
?>