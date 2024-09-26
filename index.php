<?php
include 'db.php'; // Include your database connection

// Initialize search variable
$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');

// Build the base query
$query_base = "SELECT * FROM users";

// Add search conditions if a search term is provided
if ($search) {
    $query_base .= " WHERE id LIKE '$search%' OR
                     name LIKE '%$search%' OR
                     email LIKE '%$search%' OR
                     username LIKE '%$search%' OR
                     password LIKE '%$search%' OR
                     status LIKE '$search%'";
}

// Execute the query to fetch all users based on search
$result = mysqli_query($conn, $query_base);

// Check for query error
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="https://c8.alamy.com/comp/2F6N1B5/view-report-icon-outline-view-report-vector-icon-for-web-design-isolated-on-white-background-2F6N1B5.jpg">
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-image: url('https://png.pngtree.com/background/20210715/original/pngtree-pink-blue-gradient-background-picture-image_1285856.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; color: #fff;">

    <div class="container">
        <h2 class="text-center text-dark">View Records</h2>

        <!-- Search Form -->
        <form method="GET" id="searchForm">
            <div class="row mb-3 align-items-end">
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search..." name="search" id="searchInput" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-auto ms-auto">
                    <a href="add_user.php" class="btn btn-dark">
                        <i class="fas fa-plus"></i> <b>Add New User</b>
                    </a>
                </div>
            </div>
        </form>

        <!-- Table to display users -->
        <div class="table-responsive text-center">
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-dark-subtle">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="align-content-around"><?php echo $user['id']; ?></td>
                                <td class="align-content-around"><?php echo $user['name']; ?></td>
                                <td class="align-content-around"><?php echo $user['email']; ?></td>
                                <td class="align-content-around"><?php echo $user['username']; ?></td>
                                <td class="align-content-around"><?php echo $user['password']; ?></td>
                                <td class="align-content-around">
                                    <img src="<?php echo !empty($user['photo']) ? $user['photo'] : 'default_photo.jpg'; ?>" alt="User Photo" class="user-photo">
                                </td>
                                <td class="align-content-around">
                                    <?php
                                    if ($user['status'] == 'Active') {
                                        echo '<span class="badge bg-success">Active</span>';
                                    } elseif ($user['status'] == 'Inactive') {
                                        echo '<span class="badge bg-danger">Inactive</span>';
                                    } elseif ($user['status'] == 'Pending') {
                                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                                    }
                                    ?>
                                </td>
                                <td class="align-content-around">
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(<?php echo $user['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-records text-center">No records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {  
        const searchInput = document.getElementById('searchInput');
        const userTableBody = document.querySelector('tbody');

        searchInput.addEventListener('input', function() {
            const searchQuery = this.value;

            // Use AJAX to fetch filtered results
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'search.php?search=' + encodeURIComponent(searchQuery), true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    userTableBody.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        });
    });

    function confirmDelete(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            window.location.href = 'delete_user.php?id=' + userId;
        }
    }
</script>


</body>

</html>