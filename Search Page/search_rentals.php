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
$query = "SELECT rental_id, id, username, rent_days, rental_date, title, genre, returned FROM rental WHERE username LIKE '%$searchInput%' OR id LIKE '%$searchInput%' OR title LIKE '%$searchInput%' OR genre LIKE '%$searchInput%'";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query executed successfully
if ($result) {
  // Initialize an empty array to store the rental data
  $rentals = [];

  // Fetch the rental data and add it to the array
  while ($rental = mysqli_fetch_assoc($result)) {
    $rentals[] = $rental;
  }

  // Free the result set
  mysqli_free_result($result);
} else {
  echo 'Error executing the query: ' . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);

// Send the rental data as a JSON response
echo json_encode($rentals);
?>
