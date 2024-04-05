<?php

include './inc/header.php';
require './inc/database.php';
require './inc/loggedin.php';

// Get the logged-in user's username from the session
$username = $_SESSION['username'];

// CRUD OPERATIONS
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["edit"])) {

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $username = $_POST['username'];

        $sql = "UPDATE users SET fname=?, lname=?, username=? WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fname, $lname, $username, $_SESSION['username']); // Use session username for security
        $stmt->execute();

        $stmt->close();
    } elseif (isset($_POST["delete"])) {

        $username = $_POST["username"];

        $sql = "DELETE FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $stmt->close();

        // Redirect after delete for better user experience
        header("Location: ./logout.php");
        exit;
    }
}

echo '<section class="masthead">';
echo '<div>';
echo "<h1>Welcome, $username!</h1>";

// Display data for the logged-in user

$sql = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    
    echo '<section class="person-row">';
    echo '<table class="table table-hover table-dark">';
    echo '<h3 style="color: white;">Here! You can find your account info</h3>';
    echo '<tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
            </tr>';

    // Display user data (exclude user_id)
    echo '<tr>
                <td>' . $row['fname'] . '</td>
                <td>' . $row['lname'] . '</td>
                <td>' . $row['username'] . '</td>
            </tr>';

    echo '</table>';
    echo '<br>';
    echo '<table class="table table-hover table-dark">';
    echo '<h3 style="color: white;">Here! You can Edit or Delete your account</h3>';
    
    echo '<tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
            </tr>';

    echo '<tr>';
    echo '<form method="POST">';
    echo '<td><input type="text" name="fname" value="' . $row['fname'] . '"></td>';
    echo '<td><input type="text" name="lname" value="' . $row['lname'] . '"></td>';
    echo '<td><input type="text" name="username" value="' . $row['username'] . '"></td>';
    echo '<button type="submit" name="edit" class="btn btn-primary">Edit</button>';
    echo '<button type="submit" name="delete" class="btn btn-danger">Delete</button>';
    echo '</form>';
    echo '</tr>';

    echo '</table>';
    echo '<a class="btn btn-warning" href="logout.php">Logout</a>';
    echo '</section>';
    echo '</div>';
    echo '</section>';
} else {
    echo "No user data found for the logged-in user.";
}

$stmt->close();
require './inc/footer.php';
?>