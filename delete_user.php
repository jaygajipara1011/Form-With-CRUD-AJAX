<?php
include 'db.php'; // Include your database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, retrieve the user's current photo path
    $result = mysqli_query($conn, "SELECT photo FROM users WHERE id='$id'");
    $user = mysqli_fetch_assoc($result);

    // Check if the user exists 
    if ($user) {
        $photo_path = $user['photo'];
    
        // Delete the user record
        $sql = "DELETE FROM users WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            // Delete the photo file if it exists
            if (file_exists($photo_path)) {
                unlink($photo_path); // Remove photo from server
            }
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . mysqli_error($conn);
        }
    } else {
        echo "User not found.";
    }
}

// Redirect back to the user view page after deletion
header("Location: index.php");
exit;
?>
