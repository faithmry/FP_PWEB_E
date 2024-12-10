<?php
include '../includes/db_connection.php';

// Fetch faculty data
$sql = "SELECT * FROM Faculty";
$result = $conn->query($sql);
?>
<html>
<head>
    <title>Manage Faculty</title>
</head>
<body>
    <h1>Manage Faculty</h1>
    <table border="1">
        <tr>
            <th>Faculty ID</th>
            <th>Faculty Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['faculty_id']; ?></td>
            <td><?php echo $row['faculty_name']; ?></td>
            <td><?php echo $row['faculty_email']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td>
                <a href="edit_faculty.php?id=<?php echo $row['faculty_id']; ?>">Edit</a> |
                <a href="delete_faculty.php?id=<?php echo $row['faculty_id']; ?>" onclick="return confirm('Are you sure you want to delete this faculty?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="add_faculty.php">Add New Faculty</a>
</body>
</html>
