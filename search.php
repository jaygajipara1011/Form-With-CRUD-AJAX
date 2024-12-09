<?php
include 'db.php'; // Include your database connection

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');

// Set the number of results per page
$results_per_page = 20;

// Get the current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

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

// Get the total number of results for pagination
$result_total = mysqli_query($conn, $query_base);
$total_results = mysqli_num_rows($result_total);
$total_pages = ceil($total_results / $results_per_page);

// Modify the base query to limit results for the current page
$query_base .= " LIMIT $start_from, $results_per_page";
$result = mysqli_query($conn, $query_base);

// Check for query error
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if we have results and output them as rows
if (mysqli_num_rows($result) > 0) {
    while ($user = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$user['id']}</td>
            <td>{$user['name']}</td>
            <td>{$user['email']}</td>
            <td>{$user['username']}</td>
            <td>{$user['password']}</td>
            <td><img src='".(!empty($user['photo']) ? $user['photo'] : 'default_photo.jpg')."' alt='User Photo' class='user-photo'></td>
            <td>";
        if ($user['status'] == 'Active') {
            echo '<span class="badge bg-success">Active</span>';
        } elseif ($user['status'] == 'Inactive') {
            echo '<span class="badge bg-danger">Inactive</span>';
        } elseif ($user['status'] == 'Pending') {
            echo '<span class="badge bg-warning text-dark">Pending</span>';
        }
        echo "</td>
            <td>
                <a href='edit_user.php?id={$user['id']}' class='btn btn-outline-warning btn-sm'><i class='fas fa-pencil-alt'></i></a>
                <a href='#' class='btn btn-outline-danger btn-sm' onclick='confirmDelete({$user['id']})'><i class='fas fa-trash'></i></a>
            </td>
        </tr>";
    }
} else {
    echo '<tr><td colspan="8" class="no-records text-center">No records found</td></tr>';
}

// Display pagination links
echo '<tr><td colspan="8" class="text-center">';

echo '</td></tr>';
?>
