<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-lZ8M1I2fjQQGj/2jO2qfNXOP+sXjzdmXsH1TAInAK+yw6D5wT2g4rjWS/0GH1Zf3lHHfIdid6zeyUq4d5P5sMw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css">
    <script src="script.js" defer></script>
    <style>
        /* New section styles */
        .top-section {
            height: 254px;
            background-image: url("logolib.png");
            width: 100%;
            background-size: contain;
            background-position: bottom;
            background-repeat: no-repeat;
        }

        /* Background image */
        body {
            background-image: url("libbg.jpg");
            background-size: cover;
            background-position: center;
            font-family: 'Archivo Narrow', sans-serif;
        }

        /* Logo placement */
        .logo {
            margin: 10px 0;
        }

        .logo img {
            width: 180px;
            height: 60px;
        }

        /* Header styles */
        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
        }

        /* Navigation styles */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            /* Add position relative */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            /* Add box shadow */
        }

        nav::after {
            content: '';
            position: absolute;
            bottom: -2px;
            /* Adjust the distance of the line from the navigation bar */
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #fff;
        }

        nav ul {
            display: flex;
            list-style-type: none;
        }

        nav ul li {
            margin-right: 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #555;
            color: #fff;
        }

        nav ul li #login-link {
            background-color: #333;
            padding: 10px 15px;
            border-radius: 5px;
        }

        nav ul li #login-link:hover {
            background-color: #555;
        }

        nav ul li .username {
            color: #08ceff;
        }

        nav ul li a:hover {
            color: #ff8c00;
        }

        /* Footer styles */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            position: relative;
            box-shadow: 0px -2px 4px rgba(0, 0, 0, 0.2);
        }

        footer::before {
            content: "";
            position: absolute;
            top: 3px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #fff;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-sections {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
        }

        .footer-sections>div {
            flex-basis: 30%;
            max-width: 300px;
        }

        .footer-sections h3 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .footer-sections p {
            color: #ddd;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .footer-icons {
            margin-top: 20px;
            text-align: center;
        }

        .footer-icons h3 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .footer-icons a {
            color: #fff;
            font-size: 20px;
            margin-right: 10px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-icons a:hover {
            color: #08ceff;
        }

        .footer-bottom {
            margin-top: 20px;
        }

        .footer-bottom p {
            font-size: 14px;
            color: #ddd;
            margin: 0;
        }

        /* Add hover effect to social icons */
        .footer-icons a:hover {
            color: #08ceff;
        }

        /* Adjust the font size and color of the copyright text */
        .footer-bottom p {
            font-size: 12px;
            color: #888;
        }

        /* Quick Links section */
        .quick-links {
            flex-basis: 30%;
            max-width: 300px;
        }

        .quick-links h3 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .quick-links ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .quick-links ul li {
            margin-bottom: 5px;
        }

        .quick-links ul li a {
            color: #ddd;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .quick-links ul li a:hover {
            color: #ff8c00;
            font-weight: bold;
        }

        /* Login form styles */
        main {
            padding: 50px;
            text-align: center;
        }

        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
            /* Keep the <h1> element centered */
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-image: url("feedbackbg.png");
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            padding: 30px;
            backdrop-filter: blur(5px);
            background-color: rgba(255, 255, 255, 0.5);
            text-align: left;
            /* Align the form elements to the left */
        }

        form div {
            margin-bottom: 20px;
            text-align: left;
            /* Align the form elements to the left */
        }

        label {
            display: block;
            font-size: 18px;
            margin-bottom: 5px;
            color: white;
            text-shadow: 2px 2px 4px black;
            text-align: left;
            /* Align the label text to the left */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            color: white;
            text-shadow: 2px 2px 4px black;
            background-color: transparent;
            box-sizing: border-box;
            text-align: left;
            /* Align the input and textarea text to the left */
        }

        textarea {
            height: 150px;
        }

        button[type="submit"] {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-shadow: 2px 2px 4px black;
            text-align: center;
            /* Align the button text to the center */
        }

        button[type="submit"]:hover {
            background-color: #ff8c00;
        }

        /* Custom dropdown styles */
        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            color: white;
            text-shadow: 2px 2px 4px black;
            background-color: transparent;
            box-sizing: border-box;
            -webkit-appearance: none;
            /* Remove default arrow for Chrome/Safari/Opera */
            -moz-appearance: none;
            /* Remove default arrow for Firefox */
            appearance: none;
            /* Remove default arrow for other browsers */
            background-image: url("dropdown-arrow.png");
            /* Custom arrow image */
            background-repeat: no-repeat;
            background-position: right center;
            background-size: 16px 16px;
            padding-right: 30px;
            /* Ensure enough space for the arrow */
        }

        /* Style for dropdown when it's open */
        select:focus {
            outline: none;
            border-color: #ff8c00;
            /* Add a border color to indicate focus */
            box-shadow: 0 0 5px rgba(255, 140, 0, 0.7);
            /* Add a box shadow to indicate focus */
        }

        /* Style for dropdown options */
        select option {
            background-color: #333;
            /* Background color of the dropdown options */
            color: white;
            /* Text color of the dropdown options */
        }

        .password-container {
            position: relative;
        }

        .password-input-container {
            display: flex;
            align-items: center;
        }

        .password-input-container input {
            flex: 1;
        }

        .password-toggle-icon {
            cursor: pointer;
            position: absolute;
            right: 10px;
            /* Adjust the position to align with the right side of the password field */
        }

        /* Style the icon */
        .password-toggle-icon i {
            font-size: 20px;
            color: white;
        }

        /* Style the icon when password is shown */
        .show-password .password-toggle-icon i {
            color: #ff8c00;
        }

        .translucent-box {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            padding: 30px;
        }
    </style>
</head>

<body>
    <div class="top-section"></div> <!-- New section with background image -->
    <header>
        <nav>
            <div class="logo">
                <img src="libslogo.png" alt="Library Logo">
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="booklist.php">Book List</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    // User is logged in
                    if ($_SESSION['role'] === 'staff') {
                        // User has staff role, display the "Admin Dashboard" link
                        echo '<li><a href="admin_dashboard.php">Admin Dashboard</a></li>';
                    }
                    echo '<li class="right-align"><a href="rentbooks.php" class="username">' . $_SESSION['username'] . '</a></li>';
                    echo '<li class="right-align"><a href="logout.php">Logout</a></li>';
                    echo '<li class="right-align"><a href="rentbooks.php">Profile</a></li>';
                } else {
                    // User is not logged in, display the login button
                    echo '<li><a href="login.php" id="login-link">Login</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="translucent-box">
            <div class="login-container">
                <h1 class="login-title">Login</h1>
                <?php if (isset($_SESSION["username"])) { ?>
                    <p class="logged-in">
                        <span class="username-label">Logged in as:</span>
                        <?php echo $_SESSION["username"]; ?>
                    </p>
                <?php } else { ?>
                    <form class="login-form" action="loginexecute.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group password-container">
                            <label for="password">Password</label>
                            <div class="password-input-container">
                                <input type="password" id="password" name="password" required>
                                <span class="password-toggle-icon" onclick="togglePasswordVisibility()">
                                    <i class="bx bxl bx-hide"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <button type="submit">Login</button>
                    </form>
                    <p>Don't have an account? <a href="register.html">Register here</a>.</p>
                <?php } ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <div class="logo">
                <img src="libslogo.png" alt="Company Logo">
            </div>
            <div class="footer-sections">
                <div class="contact-us">
                    <h3>Contact Us</h3>
                    <p>Phone: 123-456-7890</p>
                    <p>Email: info@example.com</p>
                </div>
                <div class="about-us">
                    <h3>About Us</h3>
                    <p>"Simplifying book management and rentals for student success."</p>
                </div>
                <div class="quick-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="booklist.php">Book List</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-icons">
            <h3>Follow Us on Social Media</h3>
            <a href="#"><i class='bx bxl-twitter'></i></a>
            <a href="#"><i class='bx bxl-facebook'></i></a>
            <a href="#"><i class='bx bxl-instagram'></i></a>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Library Management System. All rights reserved.</p>
        </div>
    </footer>
    <script>
        // Add JavaScript to toggle the password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const icon = document.querySelector(".password-toggle-icon i");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("bx-hide");
                icon.classList.add("bx-show");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("bx-show");
                icon.classList.add("bx-hide");
            }
        }

        // Add a class when the password toggle icon is clicked
        document.querySelector(".password-toggle-icon").addEventListener("click", function () {
            const passwordContainer = document.querySelector(".password-container");
            passwordContainer.classList.toggle("show-password");
        });
    </script>
</body>

</html>