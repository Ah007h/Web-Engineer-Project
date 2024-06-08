<?php
session_start();

// Check if the user is logged in and has the "staff" role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'library';

// Create a connection to the MySQL database
$conn = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch users from the database
$query = "SELECT * FROM users";

// Check if a search query is submitted
if (isset($_POST["search"])) {
    $searchInput = $_POST["searchInput"];

    // Store the search input in the session
    $_SESSION['searchInput'] = $searchInput;

    $query .= " WHERE username LIKE '%$searchInput%' OR email LIKE '%$searchInput%' OR role LIKE '%$searchInput%'";
} else {
    // Check if a search query is stored in the session
    $searchInput = isset($_SESSION['searchInput']) ? $_SESSION['searchInput'] : '';

    // Adjust the query with the search condition
    if (!empty($searchInput)) {
        $query .= " WHERE username LIKE '%$searchInput%' OR email LIKE '%$searchInput%' OR role LIKE '%$searchInput%'";
    }
}

$result = mysqli_query($conn, $query);

// Check if any users exist
if (mysqli_num_rows($result) > 0) {
    // Fetch all user records as an associative array
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // No users found
    $users = array();
    $noDataFound = true;
}

// Error messages array
$errorMessages = array();

// Handle form submissions
if (isset($_POST["add"])) {
  // Add new user
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];
  $role = $_POST["role"]; // Added role field

  // Check if the username or email already exists in the database
  $checkQuery = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
  $checkResult = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
      // Show an error message if the username or email already exists
      $errorMessages["add"] = "Username or email already exists.";
  } else {
      $query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
      if (mysqli_query($conn, $query)) {
          // Redirect to the current page to avoid form resubmission
          header("Location: " . $_SERVER["PHP_SELF"]);
          exit();
      } else {
          echo "Error adding user: " . mysqli_error($conn);
      }
  }

    } elseif (isset($_POST["update"])) {
        // Update user
        $id = $_POST["id"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $role = $_POST["role"]; // Added role field

        // Check if the updated username or email already exists in the database
        $checkQuery = "SELECT id FROM users WHERE (username = '$username' OR email = '$email') AND id != '$id'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Show an error message if the username or email already exists
            $errorMessages[$id] = "Username or email already exists.";
        } else {
            $query = "UPDATE users SET username = '$username', password = '$password', email = '$email', role = '$role' WHERE id = '$id'";
            if (mysqli_query($conn, $query)) {
                // Redirect to the current page to avoid form resubmission
                header("Location: " . $_SERVER["PHP_SELF"]);
                exit();
            } else {
                echo "Error updating user: " . mysqli_error($conn);
            }
        }
      } elseif (isset($_POST["delete"])) {
        // Delete user
        $id = $_POST["id"];
    
        $query = "DELETE FROM users WHERE id = '$id'";
        if (mysqli_query($conn, $query)) {
            // Redirect to the current page to avoid form resubmission
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit();
        } else {
            echo "Error deleting user: " . mysqli_error($conn);
        }
    }


// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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
  padding-top: 40px; /* Add top padding */
  padding-left: 40px; /* Add left padding */
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
  padding-right: 80px;
}

table th {
  background-color: #333;
  color: #fff;
}

table td {
  background-color: #fff;
}

table tr {
  padding: 10px;
}

table tr:nth-child(even) td {
  background-color: #f9f9f9;
}

.table-container {
  max-width: 1840px; /* Adjust the maximum width as per your preference */

}

input[type="text"],
button {
  padding: 10px;
  margin-right: 10px;
}

.search-container input[type="text"] {
  padding: 10px; /* Increase the padding to 15px */
  margin-right: 10px;
  margin-bottom: 20px;
}

.search-container input[type="submit"] {
  padding: 10px; /* Increase the padding to 15px */
  margin-right: 10px;
}

.search-container button {
  padding: 10px 15px;
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
  text-decoration: none; /* Remove underline */
}

/* Additional styles for view_users.php */
.error-message {
  color: red;
  margin-top: 5px;
  font-size: 12px;
}

/* Override styles for view_users.php */
h2 {
  font-size: 24px;
  margin-bottom: 20px;
}

form input[type="text"],
form input[type="submit"] {
  padding: 5px;
}

form input[type="submit"] {
  background-color: #333;
  color: #fff;
  border: none;
  cursor: pointer;
}

form input[type="submit"]:hover {
  background-color: #555;
}

a {
  color: #333;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.add-button {
  padding: 5px 5px;
  background-color: #009900 !important;
  color: #fff;
  border: none;
  cursor: pointer;
}

.add-button:hover {
  background-color: #007700 !important;
}

.delete-button {
  padding: 5px 5px;
  background-color: #FF0000 !important;
  color: #fff;
  border: none;
  cursor: pointer;
}

.delete-button:hover {
  background-color: #CC0000 !important;
}

    </style>
</head>

<body>
<div>
      <!-- Add your back button here -->
  <a href="admin_dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <h1>View Users</h1>
    <div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="searchInput" placeholder="Search by username, email, or role">
        <input type="submit" name="search" value="Search">
    </form>
    </div>
    <table class="table-container">
  <tr>
    <th>ID</th>
    <th>Username</th>
    <th>Password</th>
    <th>Email</th>
    <th>Role</th>
    <th>Actions</th>
  </tr>
  <?php if (!empty($users)): ?>
    <?php foreach ($users as $user): ?>
  <tr>
    <td><?php echo $user['id']; ?></td>
    <td><?php echo $user['username']; ?></td>
    <td><?php echo $user['password']; ?></td>
    <td><?php echo $user['email']; ?></td>
    <td><?php echo $user['role']; ?></td>
    <td>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <input type="hidden" name="original_username" value="<?php echo $user['username']; ?>">
        <input type="hidden" name="original_password" value="<?php echo $user['password']; ?>">
        <input type="hidden" name="original_email" value="<?php echo $user['email']; ?>">
        <input type="hidden" name="original_role" value="<?php echo $user['role']; ?>">
        <input type="text" name="username" value="<?php echo $user['username']; ?>">
        <input type="text" name="password" value="<?php echo $user['password']; ?>">
        <input type="text" name="email" value="<?php echo $user['email']; ?>">
        <input type="text" name="role" value="<?php echo $user['role']; ?>">
        <input type="submit" name="update" value="Update" onclick="return confirmUpdate(this.form)">
        <input type="submit" name="delete" value="Delete" class="delete-button" onclick="return confirmDelete(this.form)">
        <?php if(isset($errorMessages[$user['id']])): ?>
          <p class="error-message"><?php echo $errorMessages[$user['id']]; ?></p>
        <?php endif; ?>
      </form>
    </td>
  </tr>
<?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="6">No data found.</td>
    </tr>
  <?php endif; ?>
</table>

<h2>Add User</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirmAddUser()">
  <input type="text" name="username" placeholder="Username" required>
  <input type="text" name="password" placeholder="Password" required>
  <input type="text" name="email" placeholder="Email" required>
  <input type="text" name="role" placeholder="Role" required>
  <input type="submit" name="add" value="Add User" class="add-button">
  <?php if(isset($errorMessages["add"])): ?>
      <p class="error-message"><?php echo $errorMessages["add"]; ?></p>
  <?php endif; ?>
</form>

</body>
<script>
  function confirmUpdate(form) {
    if (!confirm("Are you sure you want to update this user?")) {
      // Reset the text fields to their original values
      form.username.value = form.original_username.value;
      form.password.value = form.original_password.value;
      form.email.value = form.original_email.value;
      form.role.value = form.original_role.value;
      return false; // Cancel the form submission
    }
    return true; // Proceed with the form submission
  }

  function confirmDelete(form) {
    return confirm("Are you sure you want to delete this user?");
  }

  function confirmAddUser() {
    return confirm("Are you sure you want to add this user?");
  }
</script>
</html>