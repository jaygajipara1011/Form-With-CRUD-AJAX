<?php
include 'db.php'; // Include your database connection

// Define how many results you want per page
$results_per_page = 10;

// Initialize search variable
$search = "";

// Check if a search term has been submitted
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// Find out the number of results stored in the database based on the search
$query = "SELECT * FROM users";
if ($search) {
    $query .= " WHERE id LIKE '%$search%' OR name LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%' OR status LIKE '$search%'";
}
$result = mysqli_query($conn, $query);

// Check for query error
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$number_of_results = mysqli_num_rows($result);

// Calculate the total number of pages
$number_of_pages = ceil($number_of_results / $results_per_page);

// Determine which page number the visitor is currently on
$page = max(1, (int) ($_GET['page'] ?? 1));

// Determine the SQL LIMIT starting number for the results on the current page
$starting_limit = ($page - 1) * $results_per_page;

// Fetch the users for the current page with search
$query .= " LIMIT $starting_limit, $results_per_page";
$result = mysqli_query($conn, $query);

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
    <style>
        .container {
            margin-top: 50px;
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
            object-fit: cover;
            /* Ensure images fit well */
        }

        .table th,
        .table td {
            border: none;
        }

        tbody tr:nth-child(odd) {
            background-color: white;
            /* Light grey for odd rows */
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
            /* White for even rows */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">View Records</h2>

        <!-- Search Form -->
        <form method="GET" id="searchForm">
            <div class="row mb-3 align-items-end">
                <div class="col-auto">
                    <input type="text" class="form-control" placeholder="Search..." name="search" id="searchInput" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-auto ms-auto">
                    <a href="add_user.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New User
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

    <script>
        // Automatically focus the search input field on page load and place cursor at the end
        window.onload = function() {
            const searchInput = document.getElementById('searchInput');
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length); // Set cursor to end
        };

        // Automatically submit the search form on input change
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function() {
            if (this.value.length < 1) {
                // Redirect to index.php if the search input is empty
                window.location.href = 'index.php';
                return;
            }
            // Delay submission to allow for typing
            setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 1000); // Adjust the delay as needed
        });

        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                // If the user confirms, redirect to the delete script
                window.location.href = 'delete_user.php?id=' + userId;
            }
        }
    </script>
</body>

</html>
