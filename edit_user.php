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
    $photo_path = $user['photo']; // Default to existing photo
    if ($_FILES['photo']['name']) {
        $photo = $_FILES['photo'];
        $photo_path = 'uploads/' . basename($photo['name']);

        if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
            // Photo uploaded successfully
        } else {
            echo "Error uploading file.";
        }
    }

    $sql = "UPDATE users SET name='$name', email='$email', username='$username', password='$password', status='$status', photo='$photo_path' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('User has been successfully updated.');
            window.location.href = 'index.php'; // Redirect after alert
        </script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
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
    <link rel="icon" type="image/png" href="https://e7.pngegg.com/pngimages/461/1024/png-clipart-computer-icons-editing-edit-icon-cdr-angle-thumbnail.png">

</head>

<body>
    <div class="container">
        <h2 class="text-center">Edit User</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name"
                    value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username"
                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" name="password"
                    value="<?php echo htmlspecialchars($user['password']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="Active" <?php echo $user['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo $user['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive
                    </option>
                    <option value="Pending" <?php echo $user['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                </select>
            </div>
            <div class="mb-3">
    <label for="photo" class="form-label">Photo</label>
    <input type="file" class="form-control" name="photo" accept="image/*">
    
    <!-- Display existing photo path -->
    <?php if (!empty($user['photo'])): ?>
        <div class="mt-2">
            <strong>Current Photo Path:</strong> <?php echo htmlspecialchars($user['photo']); ?>    
        </div>
    <?php endif; ?>
</div>


            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="index.php" class="btn btn-secondary">Back</a> <!-- Back button -->

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>