<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];
    
    // Handle file upload
    $photo = $_FILES['photo'];
    $photo_path = 'uploads/' . basename($photo['name']);
    
    if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
        // Create a raw SQL query
        $sql = "INSERT INTO users (name, email, username, password, status, photo) 
                VALUES ('$name', '$email', '$username', '$password', '$status', '$photo_path')";

        // Execute the SQL query
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                alert('User has been successfully added.');
                window.location.href = 'index.php'; // Redirect after alert
            </script>";
        } else {
            echo "Error: " . $conn->error; // Display error message
        }
    } else {
        echo "Error uploading file.";
    }
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/3450/3450043.png">

</head>
<body >
    <div class="container w-50">
        <h2 class="text-center"><b>Add New User</b></h2>
        <form method="POST" action="add_user.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label"><b>Name</b></label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><b>Email</b></label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label"><b>Username</b></label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><b>Password</b></label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label"><b>Status</b></label>
                <select class="form-select" name="status" required>
                    <option value="" selected disabled>Select Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label"><b>Photo</b></label>
                <input type="file" class="form-control" name="photo" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-dark"><b>Add User</b></button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
