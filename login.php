<?php

session_start();

include './inc/header.php';
require './inc/database.php';

// Initialize error message variable
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate username (optional)
    if (empty($username)) {
        $error_message = "Please enter your username.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verify password using password_verify
            if (password_verify($password, $row['password'])) {
                // Authentication successful
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("Location: profile.php");
                exit;
            } else {
                $error_message = "Invalid username or password!";
            }
        } else {
            $error_message = "Invalid username or password!";
        }

        $stmt->close();
    }
}

?>

<section class="signin-masthead">
  <div>
    <h3>Sign in below</h3>
    <?php if (isset($error_message)) : ?>
    <strong style="color:yellow"><?php echo $error_message; ?></strong>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  <p><input class="form-control" name="username" type="text" placeholder="Username" required /></p>
      <p><input class="form-control" name="password" type="password" placeholder="Password" required /></p>
      <input class="btn btn-primary" type="submit" value="Log in" />
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
  </div>
</section>


<?php require './inc/footer.php';?>
