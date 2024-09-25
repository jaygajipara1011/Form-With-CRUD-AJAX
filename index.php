<?php
include 'db.php'; // Include your database connection

// Define how many results you want per page
$results_per_page = 10;

// Initialize search variable
$search = "";

// Check if a search term has been submitted
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
}

// Find out the number of results stored in database based on search
$query = "SELECT * FROM users";
if ($search) {
    $query .= " WHERE id LIKE '%$search%' OR name LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%' OR status LIKE '$search%'";
}
$result = mysqli_query($conn, $query);
$number_of_results = mysqli_num_rows($result);

// Calculate the total number of pages
$number_of_pages = ceil($number_of_results / $results_per_page);

// Determine which page number visitor is currently on
$page = max(1, (int) ($_GET['page'] ?? 1));

// Determine the SQL LIMIT starting number for the results on the current page
$starting_limit = ($page - 1) * $results_per_page;

// Fetch the users for the current page with search
$query .= " LIMIT $starting_limit, $results_per_page";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container {
            margin-top: 50px;
        }

        .btn-add {
            margin-bottom: 20px;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination .page-item.active .page-link {
            background-color: #fe4196;
            border-color: #fe4196;
            color: white;
        }

        .pagination .page-item .page-link {
            color: #fa1e05;
            padding: 10px 15px;
            margin: 0 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .pagination .page-item .page-link:hover {
            background-color: #ff0090;
            color: white;
        }

        .user-photo {
            width: 100px;
            height: 100px;
            object-fit: cover; /* Ensure images fit well */
        }

        .table th,
        .table td {
            border: none;
        }

        tbody tr:nth-child(odd) {
            background-color: white; /* Light grey for odd rows */
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2; /* White for even rows */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">View Records</h2>

        <!-- Search Form -->
        <form method="POST">
            <div class="row mb-3 align-items-end">
                <div class="col-auto">
                    <input type="text" class="form-control" placeholder="Search..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-auto ms-auto">
                    <a href="add_user.php" class="btn btn-primary">Add New User</a>
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
                                <td class="align-content-around"><?php echo $user['status']; ?></td>
                                <td class="align-content-around">
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-warning btn-sm">Edit</a>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-danger btn-sm">Delete</a>
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

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                // Link to previous page
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '&search=' . urlencode($search) . '">Previous</a></li>';
                }

                // Links to pages
                for ($i = 1; $i <= $number_of_pages; $i++) {
                    echo '<li class="page-item' . ($i == $page ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '&search=' . urlencode($search) . '">' . $i . '</a></li>';
                }

                // Link to next page
                if ($page < $number_of_pages) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '&search=' . urlencode($search) . '">Next</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
