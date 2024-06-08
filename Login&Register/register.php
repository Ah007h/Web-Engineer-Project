<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Sanitize the user inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $email = mysqli_real_escape_string($conn, $email);

    // Perform any additional form validation as needed

    // Check if the username already exists in the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        // Insert the new user into the database with role "user"
        $query = "INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', 'user')";

        if (mysqli_query($conn, $query)) {
            echo "Registration successful! You will be redirected to the login page in 5 seconds.";
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "login.php";
                    }, 5000);
                </script>';
        } else {
            echo "Error registering user: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>
