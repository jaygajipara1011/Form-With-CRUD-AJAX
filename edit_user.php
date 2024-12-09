<?php
include 'db.php'; // Include your database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
    $user = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    // Handle file upload
    $photo_path = $user['photo']; // By default, keep the old photo path

    // Check if a new file was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Remove the old photo if it exists
        if (!empty($user['photo']) && file_exists($user['photo'])) {
            unlink($user['photo']); // Remove old photo
        }

        // Upload the new photo
        $photo = $_FILES['photo'];
        $photo_path = 'uploads/' . basename($photo['name']);

        // Move the uploaded file
        if (!move_uploaded_file($photo['tmp_name'], $photo_path)) {
            echo "Error uploading file.";
            exit; // Stop execution if the file upload fails
        }
    }

    // WARNING: This approach is not secure against SQL injection
    $sql = "UPDATE users SET name='$name', email='$email', username='$username', password='$password', status='$status', photo='$photo_path' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
        alert('User has been successfully updated.');
        window.location.href = 'index.php'; // Redirect after alert
    </script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/2522/2522138.png">
</head>

<body>
    <div class="container w-50">
        <h2 class="text-center"><b>Edit User</b></h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label"><b>Name</b></label>
                <input type="text" class="form-control" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><b>Email</b></label>
                <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label"><b>Username</b></label>
                <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><b>Password</b></label>
                <input type="text" class="form-control" name="password" value="<?php echo $user['password']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label"><b>Status</b></label>
                <select class="form-select" name="status">
                    <option value="Active" <?php echo $user['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo $user['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="Pending" <?php echo $user['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                </select>   
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label"><b>Photo</b></label>
                <input type="file" class="form-control" name="photo" accept="image/*">

                <!-- Display existing photo path -->
                <?php if (!empty($user['photo'])): ?>
                    <div class="mt-2">
                        <strong>Current Photo Path:</strong> <?php echo $user['photo']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><b>Update User</b></button>
            <a href="index.php" class="btn btn-secondary"><b>Back</b></a> <!-- Back button -->
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
