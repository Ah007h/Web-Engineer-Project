<?php
session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-lZ8M1I2fjQQGj/2jO2qfNXOP+sXjzdmXsH1TAInAK+yw6D5wT2g4rjWS/0GH1Zf3lHHfIdid6zeyUq4d5P5sMw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css">

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

        .hero h1,
        .hero p,
        .cta-button {
            font-family: 'Archivo Narrow', sans-serif;
        }

        .key-features h2 {
            font-family: 'Archivo Narrow', sans-serif;
        }

        .footer h3,
        .footer p,
        .footer-bottom p {
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

        nav ul li a.active {
            background-color: #555;
            color: #ff8c00;
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

        /* Hero section styles */
        .hero {
            padding: 50px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            margin: 50px;
            margin-bottom: 50px;
        }

        .hero h1 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .cta-button {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #555;
        }

        .key-features {
            text-align: center;
            margin-bottom: 20px;
        }

        .key-features-container {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(0.1px);
            padding: 30px;
            border-radius: 5px;
            margin-bottom: 50px;
        }

        /* Features section styles */
        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .feature {
            text-align: center;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 5px;
            width: 200px;
            transition: transform 0.3s ease;
        }

        .feature:hover {
            transform: scale(1.1);
        }

        .feature img {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

        .feature h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .feature p {
            font-size: 14px;
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
                <li><a href="index.php" <?php echo ($current_page === 'index.php') ? 'class="active"' : ''; ?>>Home</a>
                </li>
                <li><a href="booklist.php" <?php echo ($current_page === 'booklist.php') ? 'class="active"' : ''; ?>>Book
                        List</a></li>
                <li><a href="feedback.php" <?php echo ($current_page === 'feedback.php') ? 'class="active"' : ''; ?>>Feedback</a></li>
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
        <section class="hero">
            <h1>Welcome to the Library Management System</h1>
            <p>Manage your books and explore new ones.</p>
            <a href="booklist.php" class="cta-button">Explore Books</a>
        </section>
        <section class="key-features-container">
            <section class="key-features">
                <h2><u>Key Features</u></h2>
            </section>
            <section class="features">
                <div class="feature">
                    <img src="searchbook.png" alt="Search">
                    <h3>Search Functionality</h3>
                    <p>This feature allows students to easily find the books they are looking for.</p>
                </div>
                <div class="feature">
                    <img src="bookreview.png" alt="Book Review">
                    <h3>Book Reviews</h3>
                    <p>Students can share their thoughts and reviews on the books they have read.</p>
                </div>
                <div class="feature">
                    <img src="bookreco.png" alt="Book Recommendation">
                    <h3>Book Recommendations</h3>
                    <p>Discover new books that you might be interested in reading.</p>
                </div>
            </section>
        </section>
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


</body>

</html>