<?php
include 'db.php'; // Include your database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM users WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        // Use JavaScript to show an alert after successful deletion
        echo "<script>
    if (confirm('Are you sure you want to delete this user?')) {
        window.location.href = 'index.php'; // Redirect after alert
    } else {
        window.location.href = 'index.php'; // Redirect if user cancels
    }
</script>";

    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
