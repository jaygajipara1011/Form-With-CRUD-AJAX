<?php
session_start();
include 'db.php'; // Include your database connection

// Define how many results you want per page
$results_per_page = 10;

// Find out the number of results stored in database
$result = mysqli_query($conn, "SELECT * FROM users");
$number_of_results = mysqli_num_rows($result);

// Calculate the total number of pages
$number_of_pages = ceil($number_of_results / $results_per_page);

// Determine which page number visitor is currently on
if (!isset($_GET['page']) || $_GET['page'] < 1) {
    $page = 1;
} else {
    $page = (int) $_GET['page'];
}

// Determine the SQL LIMIT starting number for the results on the current page
$starting_limit = ($page - 1) * $results_per_page;

// Fetch the users for the current page
$result = mysqli_query($conn, "SELECT * FROM users LIMIT $starting_limit, $results_per_page");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records</title>
    <link rel="icon" type="image/png" href="https://static.thenounproject.com/png/3335014-200.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        }

        .table th,
        .table td {
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">View Records</h2>

        <!-- Admin-only button to add new user -->
        <div class="d-flex justify-content-end">
            <a href="add_user.php" class="btn btn-primary btn-add">Add New User</a>
        </div>

        <div class="table-responsive text-center d-flex">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="align-content-around"><?php echo $user['id']; ?></td>
                                <td class="align-content-around"><?php echo $user['name']; ?></td>
                                <td class="align-content-around"><?php echo $user['email']; ?></td>
                                <td class="align-content-around"><?php echo ucfirst($user['username']); ?></td>
                                <td class="align-content-around"><?php echo $user['password']; ?></td>
                                <td class="align-content-around">
                                    <img src="<?php echo $user['photo']; ?>" alt="User Photo" class="user-photo">
                                </td>
                                <td class="align-content-around">
                                    <?php
                                    $status = $user['status'];
                                    if ($status == 'Active') {
                                        echo '<span class="badge bg-success">Active</span>';
                                    } elseif ($status == 'Inactive') {
                                        echo '<span class="badge bg-danger">Inactive</span>';
                                    } elseif ($status == 'Pending') {
                                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">Unknown</span>';
                                    }
                                    ?>
                                </td>
                                <td class="align-content-around">
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-warning btn-sm">Edit</a>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-records text-center">No records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                // Link to previous page
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Previous</a></li>';
                }

                // Links to pages
                for ($i = 1; $i <= $number_of_pages; $i++) {
                    echo '<li class="page-item' . ($i == $page ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }

                // Link to next page
                if ($page < $number_of_pages) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
