<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
  // Redirect to the login page if the user is not logged in as admin
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rentalId = $_POST['rentalId'];
  $approvalStatus = $_POST['approvalStatus'];

  $hostname = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'library';

  $connection = mysqli_connect($hostname, $username, $password, $database);

  if (!$connection) {
    die('Failed to connect to the database: ' . mysqli_connect_error());
  }

  // Update the return book approval status
  $updateQuery = "UPDATE rental SET return_book_approval = '$approvalStatus' WHERE rental_id = '$rentalId'";
  $updateResult = mysqli_query($connection, $updateQuery);

  if ($updateResult) {
    if ($approvalStatus === 'Approved') {
      // Update the returned column to 'Yes'
      $updateReturnedQuery = "UPDATE rental SET returned = 'Yes' WHERE rental_id = '$rentalId'";
      $updateReturnedResult = mysqli_query($connection, $updateReturnedQuery);

      if ($updateReturnedResult) {
        $_SESSION['successMessage'] = "Return book approval status updated successfully! Book marked as returned.";
      } else {
        $_SESSION['errorMessage'] = "Failed to update the return book approval status. Please try again.";
      }
    } else {
      $_SESSION['successMessage'] = "Return book approval status updated successfully!";
    }
  } else {
    $_SESSION['errorMessage'] = "Failed to update the return book approval status. Please try again.";
  }

  mysqli_close($connection);

  header("Location: rental_book_approval.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rental Book Approval</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header>
    <nav>
      <div class="logo">
        <img src="libslogo.png" alt="Library Logo">
      </div>
      <ul>
        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
        <li class="right-align"><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <h1>Rental Book Approval</h1>
    <?php
    // Display success or error messages, if any
    if (isset($_SESSION['successMessage'])) {
      echo '<div class="success-message">' . $_SESSION['successMessage'] . '</div>';
      unset($_SESSION['successMessage']);
    }

    if (isset($_SESSION['errorMessage'])) {
      echo '<div class="error-message">' . $_SESSION['errorMessage'] . '</div>';
      unset($_SESSION['errorMessage']);
    }
    ?>

    <table id="bookTable">
      <tr>
        <th>Rental ID</th>
        <th>Book ID</th>
        <th>Username</th>
        <th>Title</th>
        <th>Genre</th>
        <th>Rent Duration (Days)</th>
        <th>Status</th>
        <th>Return Book Approval</th>
        <th>Actions</th>
      </tr>

      <?php
      $hostname = 'localhost';
      $username = 'root';
      $password = '';
      $database = 'library';

      $connection = mysqli_connect($hostname, $username, $password, $database);

      if (!$connection) {
        die('Failed to connect to the database: ' . mysqli_connect_error());
      }

      $query = "SELECT rental_id, id, title, username, genre, rent_days, returned, return_book_approval FROM rental WHERE return_book_approval = 'Pending'";
      $result = mysqli_query($connection, $query);

      if ($result) {
        if (mysqli_num_rows($result) > 0) {
          while ($rental = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $rental['rental_id'] . '</td>';
            echo '<td>' . $rental['id'] . '</td>';
            echo '<td>' . $rental['username'] . '</td>';
            echo '<td>' . $rental['title'] . '</td>';
            echo '<td>' . $rental['genre'] . '</td>';
            echo '<td>' . $rental['rent_days'] . '</td>';
            echo '<td>' . $rental['returned'] . '</td>';
            echo '<td>' . $rental['return_book_approval'] . '</td>';
            echo '<td>';
            echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST">';
            echo '<input type="hidden" name="rentalId" value="' . $rental['rental_id'] . '">';
            echo '<select name="approvalStatus">';
            echo '<option value="Approved">Approve</option>';
            echo '<option value="Rejected">Reject</option>';
            echo '</select>';
            echo '<button type="submit">Submit</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
          }
        } else {
          echo '<tr><td colspan="8">No return book requests found.</td></tr>';
        }

        mysqli_free_result($result);
      } else {
        echo 'Error executing the query: ' . mysqli_error($connection);
      }

      mysqli_close($connection);
      ?>
    </table>
  </main>
</body>

</html>