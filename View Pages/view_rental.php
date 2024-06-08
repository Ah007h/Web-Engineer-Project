<?php
session_start();

// Check if the user is logged in and has the "staff" role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
  // Redirect to the login page or display an error message
  header("Location: index.php");
  exit();
}

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

// Fetch rental data from the rental table
$query = "SELECT rental_id, id, username, rent_days, rental_date, title, genre, returned, approval_status, return_book_approval FROM rental";
$result = mysqli_query($connection, $query);

// Check if the query executed successfully
if (!$result) {
  echo 'Error executing the query: ' . mysqli_error($connection);
  mysqli_close($connection);
  exit();
}

// Fetch the search input from the POST request
$searchInput = isset($_POST['searchInput']) ? $_POST['searchInput'] : '';

// Perform the search if the search input is provided
if (!empty($searchInput)) {
  // Modify the query to include the search condition
  $searchQuery = "SELECT rental_id, id, username, rent_days, rental_date, title, genre, returned, approval_status, return_book_approval FROM rental
                  WHERE title LIKE '%$searchInput%' OR username LIKE '%$searchInput%' OR genre LIKE '%$searchInput%'";
  $result = mysqli_query($connection, $searchQuery);

  // Check if the search query executed successfully
  if (!$result) {
    echo 'Error executing the search query: ' . mysqli_error($connection);
    mysqli_close($connection);
    exit();
  }
}

// Fetch all the rental data into an array
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
  <title>Rental List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <style>
    /* Global styles */
    body {
      font-family: Arial, sans-serif;
      line-height: 1.5;
      background-color: #f1f1f1;
      margin: 0;
      background-image: url("adminwhite.png");
      background-size: cover;
      background-position: center;
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

    input[type="text"],
    button {
      padding: 10px;
      margin-right: 10px;
    }

    .search-container {
      margin-bottom: 20px;
    }

    .search-container button {
      padding: 10px;
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

    .approval-select {
      padding: 5px 10px;
    }

    .success-message {
      color: green;
      font-weight: bold;
    }

    .error-message {
      color: red;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <main>
    <div>
      <a href="admin_dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <h1>Rental List</h1>
    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Search by Book ID, Username, Title, Book Return, or Genre">
      <button id="searchButton">Search</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>Book ID</th>
          <th>Username</th>
          <th>Days Rent Book</th>
          <th>Date Rent Book</th>
          <th>Title</th>
          <th>Genre</th>
          <th>Book Returned?</th>
          <th>Approval Status</th>
          <th>Return Book Approval</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="rentalTableBody">
        <?php if (!empty($rentals)): ?>
          <?php foreach ($rentals as $rental): ?>
            <tr>
              <td><?php echo $rental['id']; ?></td>
              <td><?php echo $rental['username']; ?></td>
              <td><?php echo $rental['rent_days']; ?></td>
              <td><?php echo $rental['rental_date']; ?></td>
              <td><?php echo $rental['title']; ?></td>
              <td><?php echo $rental['genre']; ?></td>
              <td><?php echo $rental['returned']; ?></td>
              <td><?php echo $rental['approval_status']; ?></td>
              <td><?php echo $rental['return_book_approval']; ?></td>
              <td>
                <button class="deleteButton" data-rental-id="<?php echo $rental['rental_id']; ?>">
                  Delete
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="10">No rentals found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
  <script>
    // JavaScript code for the search functionality
    function searchRentals() {
      var searchInput = document.getElementById("searchInput").value;
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var rentals = JSON.parse(xhr.responseText);
            displayRentals(rentals);
            addDeleteButtonListeners(); // Add event listeners to delete buttons
          } else {
            console.log('Error: ' + xhr.status);
          }
        }
      };
      xhr.open("POST", "search_rentals.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("searchInput=" + searchInput);
    }

    // Function to add event listeners to delete buttons
    function addDeleteButtonListeners() {
      var deleteButtons = document.getElementsByClassName("deleteButton");
      for (var i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event) {
          var rentalId = event.target.getAttribute("data-rental-id");
          if (confirm("Are you sure you want to delete this rental?")) {
            deleteRental(rentalId);
          }
        });
      }
    }

    function displayRentals(rentalArray) {
      var rentalTableBody = document.getElementById("rentalTableBody");
      rentalTableBody.innerHTML = "";

      if (rentalArray.length > 0) {
        rentalArray.forEach(function(rental) {
          var rentalRow = document.createElement("tr");
          rentalRow.innerHTML = '<td>' + rental.id + '</td>' +
            '<td>' + rental.username + '</td>' +
            '<td>' + rental.rent_days + '</td>' +
            '<td>' + rental.rental_date + '</td>' +
            '<td>' + rental.title + '</td>' +
            '<td>' + rental.genre + '</td>' +
            '<td>' + rental.returned + '</td>' +
            '<td>' + rental.approval_status + '</td>' +
            '<td>' + rental.return_book_approval + '</td>';

          var deleteButton = document.createElement("button");
          deleteButton.className = "deleteButton";
          deleteButton.setAttribute("data-rental-id", rental.rental_id);
          deleteButton.textContent = "Delete";
          rentalRow.appendChild(deleteButton);

          rentalTableBody.appendChild(rentalRow);
        });
      } else {
        var noRentalsRow = document.createElement("tr");
        noRentalsRow.innerHTML = '<td colspan="10">No rentals found.</td>';
        rentalTableBody.appendChild(noRentalsRow);
      }
    }

    document.getElementById("searchButton").addEventListener("click", searchRentals);

    // JavaScript code for the delete functionality
    function deleteRental(rentalId) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
              // Remove the deleted row from the table
              var deletedRow = document.querySelector('[data-rental-id="' + rentalId + '"]').closest("tr");
              deletedRow.parentNode.removeChild(deletedRow);
            } else {
              console.log('Error: ' + response.message);
            }
          } else {
            console.log('Error: ' + xhr.status);
          }
        }
      };
      xhr.open("POST", "delete_rental.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("rentalId=" + rentalId);
    }

    // Add event listeners to the delete buttons
    document.addEventListener("DOMContentLoaded", function() {
      var deleteButtons = document.getElementsByClassName("deleteButton");
      for (var i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event) {
          var rentalId = event.target.getAttribute("data-rental-id");
          if (confirm("Are you sure you want to delete this rental?")) {
            deleteRental(rentalId);
          }
        });
      }
    });
  </script>
</body>

</html>