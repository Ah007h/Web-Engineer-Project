<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect to the login page if the user is not logged in
  header("Location: login.php");
  exit;
}

// Check if the user is a staff member
$isStaff = isset($_SESSION['role']) && $_SESSION['role'] === 'staff';

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

// Fetch rental data from the rental table based on user ID
$userId = $_SESSION['user_id'];
$query = "SELECT id, title, genre, due_date FROM rental WHERE user_id = $userId";
$result = mysqli_query($connection, $query);

// Check if the query executed successfully
if ($result) {
  // Check if there are any rows returned
  if (mysqli_num_rows($result) > 0) {
    $rentals = mysqli_fetch_all($result, MYSQLI_ASSOC);
  } else {
    $rentals = [];
  }

  // Free the result set
  mysqli_free_result($result);
} else {
  echo 'Error executing the query: ' . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Head section omitted for brevity -->
</head>

<body>
  <header>
    <!-- Navigation bar omitted for brevity -->
  </header>
  <main>
    <h1>Rental List</h1>
    <div>
      <!-- Search input and button omitted for brevity -->
    </div>
    <table>
      <tr>
        <th>Book ID</th>
        <th>Title</th>
        <th>Genre</th>
        <th>Due Date</th>
      </tr>

      <?php if (!empty($rentals)): ?>
        <?php foreach ($rentals as $rental): ?>
          <tr>
            <td>
              <?php echo $rental['id']; ?>
            </td>
            <td>
              <?php echo $rental['title']; ?>
            </td>
            <td>
              <?php echo $rental['genre']; ?>
            </td>
            <td>
              <?php echo $rental['due_date']; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="4">No rentals found.</td>
        </tr>
      <?php endif; ?>
    </table>
  </main>
  <footer>
    <!-- Footer section omitted for brevity -->
  </footer>
  <script>
    // JavaScript code omitted for brevity
  </script>
</body>

</html>