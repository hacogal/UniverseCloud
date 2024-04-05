<?php
include './inc/header.php';
require './inc/database.php';
require './inc/loggedin.php';

// Function to fetch images from the database
function fetchImages($conn) {
    $imagelist = array();

    $stmt = $conn->prepare('SELECT * FROM images');
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $imagelist = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        return "Error fetching images: " . $conn->error;
    }

    return $imagelist;
}

// Function to delete an image from the database
function deleteImage($conn, $imageId) {
    $stmt = $conn->prepare('DELETE FROM images WHERE id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $imageId);
        if ($stmt->execute()) {
            return "Image deleted successfully!";
        } else {
            return "Error deleting image: " . $conn->error;
        }
        $stmt->close();
    } else {
        return "Error preparing delete statement: " . $conn->error;
    }
}

// Check if the delete request is received via POST
$deleteMessage = ''; // Variable to store delete operation message
if (isset($_POST['delete_image'])) {
    $imageIdToDelete = $_POST['image_id'];
    $deleteMessage = deleteImage($conn, $imageIdToDelete);
}

// Fetch images from the database
$imagelist = fetchImages($conn);
?>

<section class="view-masthead">
    <div>
        <h1>Welcome <?php echo $_SESSION['username']; ?> to the Gallery</h1>
        <p style="color:white"><?php echo $deleteMessage; ?></p> <!-- Display delete operation message here -->
    
        <section class="image-row">
            <?php foreach ($imagelist as $image) : ?>
                <div class="image-container">
                    <img src="<?= $image['image'] ?>" alt="<?= $image['name'] ?>" class="img-fluid">
                    <p><?= $image['name'] ?></p>
                    <form method="POST">
                        <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                        <input class="btn btn-outline-danger" type="submit" name="delete_image" value="Delete">
                    </form>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</section>

<?php include './inc/footer.php'; ?>
