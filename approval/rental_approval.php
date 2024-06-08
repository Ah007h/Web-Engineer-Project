<?php
session_start();

// Check if the user is logged in and has the "staff" role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
  // Redirect to the login page or display an error message
  header("Location: index.php");
  exit();
}

// Check if the rentalId is provided in the query string
if (isset($_GET['rentalId'])) {
  // Get the rentalId from the query string
  $rentalId = $_GET['rentalId'];

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

  // Check if the action is 'approve'
  if (isset($_GET['action']) && $_GET['action'] === 'approve') {
    // Update the rental approval status to 'Approved'
    $updateQuery = "UPDATE rental SET approval_status = 'Approved' WHERE rental_id = '$rentalId'";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
      // Set success message in session variable
      $_SESSION['successMessage'] = "Rental request approved successfully!";
    } else {
      // Set error message in session variable
      $_SESSION['errorMessage'] = "Failed to approve the rental request. Please try again.";
    }
  } elseif (isset($_GET['action']) && $_GET['action'] === 'reject') {
    // Update the rental approval status to 'Rejected'
    $updateQuery = "UPDATE rental SET approval_status = 'Rejected' WHERE rental_id = '$rentalId'";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
      // Set success message in session variable
      $_SESSION['successMessage'] = "Rental request rejected successfully!";
    } else {
      // Set error message in session variable
      $_SESSION['errorMessage'] = "Failed to reject the rental request. Please try again.";
    }
  }

  // Close the database connection
  mysqli_close($connection);

  // Redirect back to the rental approval page
  header("Location: rental_approval.php");
  exit();
}

// Continue with the existing code for displaying the rental approval page

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

// Fetch rental data from the rental table with 'Pending' approval status
$query = "SELECT rental_id, id, username, rent_days, rental_date, title, genre, returned FROM rental WHERE approval_status = 'Pending'";
$result = mysqli_query($connection, $query);

// Check if the query executed successfully
if (!$result) {
  echo 'Error executing the query: ' . mysqli_error($connection);
  mysqli_close($connection);
  exit();
}

// Fetch the rental data into an array
$rentals = [];
while ($row = mysqli_fetch_assoc($result)) {
  $rentals[] = $row;
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rental Approval</title>
  <!-- Add your CSS styles here -->
</head>

<body>
  <h1>Rental Approval</h1>
  <?php if (!empty($rentals)): ?>
    <table>
      <thead>
        <tr>
          <th>Rental ID</th>
          <th>Book ID</th>
          <th>Username</th>
          <th>Days Rented</th>
          <th>Rental Date</th>
          <th>Title</th>
          <th>Genre</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rentals as $rental): ?>
          <tr>
            <td><?php echo $rental['rental_id']; ?></td>
            <td><?php echo $rental['id']; ?></td>
            <td><?php echo $rental['username']; ?></td>
            <td><?php echo $rental['rent_days']; ?></td>
            <td><?php echo $rental['rental_date']; ?></td>
            <td><?php echo $rental['title']; ?></td>
            <td><?php echo $rental['genre']; ?></td>
            <td>
              <a href="rental_approval.php?action=approve&rentalId=<?php echo $rental['rental_id']; ?>">Approve</a>
              <a href="rental_approval.php?action=reject&rentalId=<?php echo $rental['rental_id']; ?>">Reject</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No rental requests pending approval.</p>
  <?php endif; ?>
</body>

</html>