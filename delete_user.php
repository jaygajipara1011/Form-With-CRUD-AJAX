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
    
            // Use JavaScript to show an alert after successful deletion
            echo "<script>
                alert('User has been successfully deleted.');
                window.location.href = 'index.php'; // Redirect after alert
            </script>";
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        echo "User not found.";
    }
    
}
?>
