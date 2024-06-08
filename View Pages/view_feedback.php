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

// Fetch all feedback data from the feedback table
$query = "SELECT id, name, email, message, created_at FROM feedback";
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
    $searchQuery = "SELECT id, name, email, message, created_at FROM feedback
                  WHERE id LIKE '%$searchInput%' OR email LIKE '%$searchInput%' OR name LIKE '%$searchInput%'";
    $result = mysqli_query($connection, $searchQuery);

    // Check if the search query executed successfully
    if (!$result) {
        echo 'Error executing the search query: ' . mysqli_error($connection);
        mysqli_close($connection);
        exit();
    }
}

// Fetch all the feedback data into an array
$feedbacks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $feedbacks[] = $row;
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback List</title>
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

input[type="text"],
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

table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 40px;
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
    </style>
</head>

<body>
    <main>
        <div>
            <!-- Add your back button here -->
            <a href="admin_dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
        <h1>Feedback List</h1>
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search by ID, Name, or Email">
            <button id="searchButton">Search</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="feedbackTableBody">
                <?php if (!empty($feedbacks)): ?>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <tr>
                            <td>
                                <?php echo $feedback['id']; ?>
                            </td>
                            <td>
                                <?php echo $feedback['name']; ?>
                            </td>
                            <td>
                                <?php echo $feedback['email']; ?>
                            </td>
                            <td>
                                <?php echo $feedback['message']; ?>
                            </td>
                            <td>
                                <?php echo $feedback['created_at']; ?>
                            </td>
                            <td>
  <button class="deleteButton" data-feedback-id="<?php echo $feedback['id']; ?>">
    Delete
  </button>
</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No feedback found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    <script>
// JavaScript code for the search functionality
function searchFeedbacks() {
    var searchInput = document.getElementById("searchInput").value;
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var feedbacks = JSON.parse(xhr.responseText);
                displayFeedbacks(feedbacks);
            } else {
                console.log('Error: ' + xhr.status);
            }
        }
    };
    xhr.open("POST", "search_feedback.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("searchInput=" + searchInput);
}

function displayFeedbacks(feedbackArray) {
    var feedbackTableBody = document.getElementById("feedbackTableBody");
    feedbackTableBody.innerHTML = "";

    if (feedbackArray.length > 0) {
        feedbackArray.forEach(function(feedback) {
            var feedbackRow = document.createElement("tr");
            feedbackRow.innerHTML =
                '<td>' + feedback.id + '</td>' +
                '<td>' + feedback.name + '</td>' +
                '<td>' + feedback.email + '</td>' +
                '<td>' + feedback.message + '</td>' +
                '<td>' + feedback.created_at + '</td>' +
                '<td><button class="deleteButton" data-feedback-id="' + feedback.id + '">Delete</button></td>';

            feedbackTableBody.appendChild(feedbackRow);
        });
    } else {
        var noFeedbacksRow = document.createElement("tr");
        noFeedbacksRow.innerHTML = '<td colspan="6">No feedback found.</td>';
        feedbackTableBody.appendChild(noFeedbacksRow);
    }

    // Re-attach event listeners to the delete buttons
    attachDeleteButtonListeners();
}

function attachDeleteButtonListeners() {
    var deleteButtons = document.getElementsByClassName("deleteButton");
    for (var i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event) {
            var feedbackId = event.target.getAttribute("data-feedback-id");
            if (confirm("Are you sure you want to delete this feedback?")) {
                deleteFeedback(feedbackId);
            }
        });
    }
}

document.getElementById("searchButton").addEventListener("click", searchFeedbacks);

// JavaScript code for the delete functionality
function deleteFeedback(feedbackId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Remove the deleted row from the table
                    var deletedRow = document.querySelector('[data-feedback-id="' + feedbackId + '"]').parentNode.parentNode;
                    deletedRow.parentNode.removeChild(deletedRow);
                } else {
                    console.log('Error: ' + response.message);
                }
            } else {
                console.log('Error: ' + xhr.status);
            }
        }
    };
    xhr.open("POST", "delete_feedback.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("feedbackId=" + feedbackId);
}

// Attach event listeners to the delete buttons initially
attachDeleteButtonListeners();

    </script>
</body>

</html>