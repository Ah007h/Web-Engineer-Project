<?php
session_start();

// Check if the user is logged in and has the "staff" role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
  // Redirect to the login page or display an error message
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-lZ8M1I2fjQQGj/2jO2qfNXOP+sXjzdmXsH1TAInAK+yw6D5wT2g4rjWS/0GH1Zf3lHHfIdid6zeyUq4d5P5sMw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css">
  <title>Admin Dashboard</title>
  <style>
    /* Global styles */
    body {
      background-image: url("adminwhite.png");
            background-size: cover;
            background-position: center;
      font-family: 'Archivo Narrow', sans-serif;
      line-height: 1.5;
      background-color: #f1f1f1;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    main {
      background-color: #fff;
      border-radius: 100px;
      border: 2px solid black;
      box-shadow: 20px 20px 0px rgba(0, 0, 0, 0.1);
      padding: 40px;
      text-align: center;
      position: relative;
    }

    h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    h2 {
      font-size: 22px;
      margin-bottom: 10px;
    }

    ul {
      list-style-type: none;
      margin-bottom: 20px;
      padding-left: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    li {
      margin-bottom: 10px;
      width: 200px;
      padding: 10px;
      background-color: #f9f9f9;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    li:hover {
      background-color: #e8e8e8;
    }

    li a {
      display: block;
      color: #1a73e8;
      text-decoration: none;
      transition: color 0.3s;
    }

    li a:hover {
      color: #0d47a1;
    }

    .homepage-button {
      position: fixed;
      top: 20px;
      left: 20px;
    }

    .homepage-button a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #1a73e8;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s;
    }

    .homepage-button a:hover {
      background-color: #0d47a1;
    }
  </style>
</head>

<body>
  <!-- Main Content -->
  <main>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <h2>Choose an Option:</h2>
    <ul>
      <li><a href="view_users.php">View Users</a></li>
      <li><a href="view_books.php">View Books</a></li>
      <li><a href="view_rental.php">View Rental</a></li>
      <li><a href="view_feedback.php">View Feedback</a></li>
      <li><a href="view_approval.php">View Rent/Return Book Approval</a></li>
    </ul>
  </main>

  <div class="homepage-button">
    <a href="index.php">Go to homepage</a>
  </div>
</body>

</html>