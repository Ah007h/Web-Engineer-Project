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
  header("Location: view_approval.php");
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

<?php

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

  header("Location: view_approval.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rental Approval</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <style>
    /* Global styles */
    body {
      background-image: url("adminwhite.png");
      background-size: cover;
      background-position: center;
      font-family: Arial, sans-serif;
      line-height: 1.5;
      background-color: #f1f1f1;
      margin: 0;
    }

    header {
      background-color: #333;
      color: #fff;
      padding: 20px;
    }

    nav ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
    }

    nav ul li {
      display: inline;
      margin-right: 10px;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
    }

    nav ul li a:hover {
      font-weight: bold;
    }

    main {
      padding: 40px;
    }

    h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table th,
    table td {
      padding: 10px;
      text-align: left;
    }

    table th {
      background-color: #333;
      color: #fff;
    }

    table td {
      background-color: #fff;
    }

    table tr:nth-child(even) td {
      background-color: #f9f9f9;
    }

    select,
    button {
      padding: 10px;
      margin-right: 10px;
    }

    .search-container {
      margin-bottom: 20px;
    }

    .search-container button {
      padding: 10px 10px;
      margin-right: 10px;
      background-color: #333;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    .search-container button:hover {
      background-color: #555;
    }

    .back-button {
      top: 20px;
      left: 40px;
      font-size: 16px;
      padding: 10px;
      margin-bottom: 40px;
      background-color: #333;
      color: #fff;
      text-decoration: none;
    }

    .back-button:hover {
      background-color: #555;
    }

    .deleteButton {
      padding: 5px 5px;
      background-color: #FF0000 !important;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    .deleteButton:hover {
      background-color: #CC0000 !important;
    }

    /* Additional styles for rental approval table */
    .success-message {
      color: #006400;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .error-message {
      color: #FF0000;
      font-weight: bold;
      margin-bottom: 10px;
    }

    #bookTable {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    #bookTable th,
    #bookTable td {
      padding: 10px;
      text-align: left;
    }

    #bookTable th {
      background-color: #333;
      color: #fff;
    }

    #bookTable td {
      background-color: #fff;
    }

    #bookTable tr:nth-child(even) td {
      background-color: #f9f9f9;
    }

    #bookTable select,
    #bookTable button {
      padding: 5px;
      margin-right: 5px;
    }

    #bookTable .back-button {
      top: 20px;
      left: 40px;
      font-size: 16px;
      padding: 10px;
      margin-bottom: 40px;
      background-color: #333;
      color: #fff;
      text-decoration: none;
    }

    #bookTable .back-button:hover {
      background-color: #555;
    }
  </style>
</head>


<body>
  <main>
    <div>
      <!-- Add your back button here -->
      <a href="admin_dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <h1>Rental Approval</h1>
        <?php if (!empty($rentals)): ?>
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
              <td>
                <?php echo $rental['rental_id']; ?>
              </td>
              <td>
                <?php echo $rental['id']; ?>
              </td>
              <td>
                <?php echo $rental['username']; ?>
              </td>
              <td>
                <?php echo $rental['rent_days']; ?>
              </td>
              <td>
                <?php echo $rental['rental_date']; ?>
              </td>
              <td>
                <?php echo $rental['title']; ?>
              </td>
              <td>
                <?php echo $rental['genre']; ?>
              </td>
              <td>
                <a href="view_approval.php?action=approve&rentalId=<?php echo $rental['rental_id']; ?>">Approve</a>
                <a href="view_approval.php?action=reject&rentalId=<?php echo $rental['rental_id']; ?>">Reject</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
            <p>No rental requests pending approval.</p>
        <?php endif; ?>

        <h1>Rental Book Approval</h1>

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


    
  </main>
</body>

</html>