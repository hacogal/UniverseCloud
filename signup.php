<?php

include './inc/header.php';
require './inc/database.php';

$error_message = ''; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm'];

    // Validate input fields
    if (empty($fname) || empty($lname) || empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt_check_username = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt_check_username->bind_param("s", $username);
        $stmt_check_username->execute();
        $result_username = $stmt_check_username->get_result();

        if ($result_username->num_rows > 0) {
            $error_message = "Username already exists. Please choose a different one.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $stmt_insert_user = $conn->prepare("INSERT INTO users (fname, lname, username, password) VALUES (?, ?, ?, ?)");
            $stmt_insert_user->bind_param("ssss", $fname, $lname, $username, $hashed_password);
            if ($stmt_insert_user->execute()) {
                // Registration successful, redirect to login page
                header("Location: saved_user.php");
                exit;
            } else {
                // Error occurred, handle appropriately
                $error_message = "Error: The profile was not created. Please try again.";
            }
        }
    }
}


?>

<section class="signin-masthead">
    <div>
    <h3>Don't have an account, then sign up below!</h3>
    <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="signup.php">
        	<p><input name="fname" type="text" placeholder="First Name" required/>
        	<input name="lname" type="text" placeholder="Last Name" required /></p>
        	<p><input class="form-control" name="username" type="text" placeholder="Username" required /></p>
        	<p><input class="form-control" name="password" type="password" placeholder="Password" required /></p>
        	<p><input class="form-control" name="confirm" type="password" placeholder="Confirm Password" required /></p>
          <input class="btn btn-primary" type="submit" name="submit" value="Sign up" />
        </form>
    </div>
  </section>

<?php require './inc/footer.php';?>