<?php
include '../includes/db_connection.php';

// Fetch data students
$sql = "SELECT * FROM Student";
$result = $conn->query($sql);
?>
<html>
<head>
    <title>Manage Students</title>
</head>
<body>
    <h1>Manage Students</h1>
    <table border="1">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Enrollment Year</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['student_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td><?php echo $row['enrollment_year']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>