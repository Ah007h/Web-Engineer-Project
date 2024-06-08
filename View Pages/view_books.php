<?php
session_start();

// Check if the user is logged in and has the "staff" role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    // Redirect to the login page or display an error message
    header("Location: index.php");
    exit();
}
?>

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

// Function to fetch books from the booklist table based on search input
function fetchBooks($conn, $searchInput) {
  $query = "SELECT * FROM booklist";

  if (!empty($searchInput)) {
      $query .= " WHERE title LIKE '%$searchInput%' OR genre LIKE '%$searchInput%'";
  }

  $result = mysqli_query($conn, $query);
  $books = mysqli_fetch_all($result, MYSQLI_ASSOC);

  // Fetch error messages for each book
  global $errorMessages;
  foreach ($books as &$book) {
      if (isset($errorMessages[$book['id']])) {
          $book['error'] = $errorMessages[$book['id']];
      }
  }

  return $books;
}



// Function to insert a new book into the booklist table
function insertBook($conn, $title, $genre) {
    // Check if the book title already exists
    $checkQuery = "SELECT id FROM booklist WHERE title = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $title);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Show an error message if the book title already exists
        $errorMessages["add"] = "Book with the same title already exists.";
    } else {
        // Proceed with the insertion
        $insertQuery = "INSERT INTO booklist (title, genre) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ss", $title, $genre);
        mysqli_stmt_execute($stmt);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();
    }
}

// Function to update an existing book in the booklist table
function updateBook($conn, $id, $title, $genre) {
  global $errorMessages; // Define $errorMessages as a global variable

  // Check if the book title already exists excluding the current book being updated
  $checkQuery = "SELECT id FROM booklist WHERE title = ? AND id != ?";
  $stmt = mysqli_prepare($conn, $checkQuery);
  mysqli_stmt_bind_param($stmt, "si", $title, $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  if (mysqli_stmt_num_rows($stmt) > 0) {
      // Show an error message if the book title already exists
      $errorMessages[$id] = "Book with the same title already exists.";
  } else {
      // Proceed with the update
      $updateQuery = "UPDATE booklist SET title = ?, genre = ? WHERE id = ?";
      $stmt = mysqli_prepare($conn, $updateQuery);
      mysqli_stmt_bind_param($stmt, "ssi", $title, $genre, $id);
      mysqli_stmt_execute($stmt);
      header("Location: " . $_SERVER["PHP_SELF"]);
      exit();
  }
}



// Function to delete a book from the booklist table
function deleteBook($conn, $id, $searchInput) {
  $query = "DELETE FROM booklist WHERE id = '$id'";
  if (mysqli_query($conn, $query)) {
      return true;
  } else {
      echo "Error deleting book: " . mysqli_error($conn);
      return false;
  }
}


// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["add"])) {
      $title = $_POST["title"];
      $genre = $_POST["genre"];
      insertBook($conn, $title, $genre);
  } elseif (isset($_POST["update"])) {
      $id = $_POST["id"];
      $title = $_POST["title"];
      $genre = $_POST["genre"];
      updateBook($conn, $id, $title, $genre);
  } elseif (isset($_POST["delete"])) {
      $id = $_POST["id"];
      $searchInput = $_POST["searchInput"] ?? ""; // Get the search input from the POST data
      if (deleteBook($conn, $id, $searchInput)) {
          // Redirect to the current page with the search input parameter to maintain search results
          header("Location: " . $_SERVER["PHP_SELF"] . "?searchInput=" . urlencode($searchInput));
          exit();
      }
  }
}



// Fetch all books from the booklist table
if (isset($_POST["search"])) {
  $searchInput = $_POST["searchInput"];
  $books = fetchBooks($conn, $searchInput);
} else {
  $books = fetchBooks($conn, "");
}


// Error messages array
$errorMessages = array();

// Handle form submissions
if (isset($_POST["add"])) {
  // Add new book
  $title = $_POST["title"];
  $genre = $_POST["genre"];

  // Check if the book title already exists in the database
  $checkQuery = "SELECT id FROM booklist WHERE title = '$title'";
  $checkResult = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
      // Show an error message if the book title already exists
      $errorMessages["add"] = "Book with the same title already exists.";
  } else {
      insertBook($conn, $title, $genre);
      header("Location: " . $_SERVER["PHP_SELF"]);
      exit();
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
  <title>View Books</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="styles.css">
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
  <h1>View Books</h1>
  <div class="search-container">
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="text" name="searchInput" placeholder="Search by title or genre">
    <input type="submit" name="search" value="Search">
  </form>
</div>

  <?php if (count($books) > 0): ?>
  <table class="table-container">
  <tr>
    <th>ID</th>
    <th>Title</th>
    <th>Genre</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($books as $book): ?>
  <tr>
    <td><?php echo $book['id']; ?></td>
    <td><?php echo $book['title']; ?></td>
    <td><?php echo $book['genre']; ?></td>
    <td>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirmUpdate(this)">
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
        <input type="hidden" name="original_title" value="<?php echo $book['title']; ?>">
        <input type="hidden" name="original_genre" value="<?php echo $book['genre']; ?>">
        <input type="text" name="title" value="<?php echo $book['title']; ?>">
        <input type="text" name="genre" value="<?php echo $book['genre']; ?>">
        <input type="submit" name="update" value="Update">
        <button type="button" class="delete-button" onclick="confirmDeleteBook('<?php echo $book['id']; ?>')">Delete</button>
        <?php if (isset($book['error'])): ?>
          <p class="error-message"><?php echo $book['error']; ?></p>
        <?php endif; ?>
      </form>
    </td>
  </tr>
<?php endforeach; ?>
</table>
<?php else: ?>
  <p>No data found.</p>
<?php endif; ?>


<h2>Add Book</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirmAddBook()">
  <input type="text" name="title" placeholder="Title" required>
  <input type="text" name="genre" placeholder="Genre" required>
  <input type="submit" name="add" value="Add Book" class="add-button">
  <?php if (isset($errorMessages["add"])): ?>
    <p class="error-message"><?php echo $errorMessages["add"]; ?></p>
  <?php endif; ?>
</form>

</body>
<script>
  function confirmUpdate(form) {
    if (!confirm("Are you sure you want to update this book?")) {
      // Reset the text fields to their original values
      form.title.value = form.original_title.value;
      form.genre.value = form.original_genre.value;
      return false; // Cancel the form submission
    }
    return true; // Proceed with the form submission
  }

  function confirmDeleteBook(bookId) {
    if (confirm("Are you sure you want to delete this book?")) {
      // Create a hidden input element to hold the book ID
      var input = document.createElement("input");
      input.type = "hidden";
      input.name = "id";
      input.value = bookId;

      // Create a hidden input element to trigger the delete action
      var deleteInput = document.createElement("input");
      deleteInput.type = "hidden";
      deleteInput.name = "delete";

      // Append the hidden inputs to the form
      var form = document.createElement("form");
      form.method = "post";
      form.appendChild(input);
      form.appendChild(deleteInput);

      // Submit the form
      document.body.appendChild(form);
      form.submit();
    }
  }

  function confirmAddBook() {
    return confirm("Are you sure you want to add this book?");
  }
</script>


</html>