<?php
include './inc/header.php';
require './inc/database.php';
require './inc/loggedin.php';

if (isset($_POST['submit'])) {
    $valid_extensions = array("png", "jpeg", "jpg");

    // Count total files
    $countfiles = count($_FILES['files']['name']);

    for ($i = 0; $i < $countfiles; $i++) {
        $filename = $_FILES['files']['name'][$i];
        $target_file = './uploads/' . $filename;
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file extension
        if (in_array($file_extension, $valid_extensions)) {
            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $target_file)) {
                // Execute query to insert image info into the database
                $query = "INSERT INTO images (name, image) VALUES (?, ?)";
                $statement = $conn->prepare($query);
                $statement->bind_param("ss", $filename, $target_file);
                $statement->execute();
                $statement->close();
                $success_message = "Image(s) uploaded successfully.";
            } else {
                $error_message = "Error uploading file: " . $_FILES['files']['name'][$i];
            }
        } else {
            $error_message = "Invalid file type: " . $_FILES['files']['name'][$i];
        }
    }
}

?>

<section class="add-image-page">
    <div>
        <h1>Uploading Images</h1>
        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <section class="form-row">
            <form method='post' action='' enctype='multipart/form-data'>
                <p><input class="btn btn-outline-secondary" type='file' name='files[]' multiple /></p>
                <input class="btn btn-success" type='submit' value='Submit' name='submit' />
                <a href="view.php" class="btn btn-primary">View Uploads</a>
            </form>
        </section>
    </div>
</section>

<?php require './inc/footer.php'; ?>
