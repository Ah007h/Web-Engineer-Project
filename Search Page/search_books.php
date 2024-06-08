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

// Retrieve the search text from the AJAX request
$searchText = $_POST['searchText'];

// Escape special characters in the search text
$searchText = mysqli_real_escape_string($connection, $searchText);

// Construct the search query
$query = "SELECT id, title, genre FROM booklist WHERE title LIKE '%$searchText%' OR genre LIKE '%$searchText%'";

// Execute the query
$result = mysqli_query($connection, $query);

// Check if the query executed successfully
if ($result) {
    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        // Output the table header cells
        echo '<tr>';
        echo '<th>Book ID</th>';
        echo '<th>Title</th>';
        echo '<th>Genre</th>';
        echo '<th>Actions</th>';
        echo '</tr>';

        // Iterate through each row and display the book data
        while ($book = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $book['id'] . '</td>';
            echo '<td>' . $book['title'] . '</td>';
            echo '<td>' . $book['genre'] . '</td>';
            echo '<td><a href="rent.php?bookId=' . $book['id'] . '" class="rentButton" data-book-id="' . $book['id'] . '">Rent</a></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4">No books found.</td></tr>';
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    echo 'Error executing the query: ' . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);
?>