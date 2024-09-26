<?php
include 'db.php'; // Include your database connection

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');

// Build the query
$query = "SELECT * FROM users";
if ($search) {
    $query .= " WHERE id LIKE '%$search%' OR
     name LIKE '%$search%' OR
     email LIKE '%$search%' OR
     username LIKE '%$search%' OR
     status LIKE '$search%'";
}
$query .= " LIMIT 10"; // Limit to 10 results

$result = mysqli_query($conn, $query);

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
?>
