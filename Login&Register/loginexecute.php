<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Perform the necessary validation checks and authentication logic
    if (validateLogin($username, $password, $role)) {
        // Login successful, set the session variables
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role;

        // Redirect to the appropriate page based on the role
        if ($role === "user") {
            header("Location: index.php"); // Replace with the user home page URL
            exit;
        } elseif ($role === "staff") {
            header("Location: admin_dashboard.php"); // Replace with the staff home page URL
            exit;
        }
    } else {
        // Invalid login, display an error message and redirect after a delay
        echo '<script>
                alert("Invalid login credentials. Please try again.");
                window.location.href = "login.php";
            </script>';
    }
}

function validateLogin($username, $password, $role) {
    // Implement your validation and authentication logic here
    // Query the database to check if the provided username, password, and role combination is valid
    // You should use prepared statements and proper hashing for password security

    // Example code to validate login against the database
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $database = 'library';

    // Create a connection to the MySQL database
    $conn = mysqli_connect($host, $dbUsername, $dbPassword, $database);

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize the user inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query the database to validate the username, password, and role combination
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = '$role'";
    $result = mysqli_query($conn, $query);

    // Check if a matching user record was found
    if (mysqli_num_rows($result) == 1) {
        return true; // Valid login credentials
    }

    return false; // Invalid login credentials
}
?>
