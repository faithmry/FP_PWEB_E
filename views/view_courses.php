<?php
include '../includes/db_connection.php';

// Fetch courses for the student
$sql = "SELECT * FROM Course";
$result = $conn->query($sql);
?>
<html>
<head>
    <title>View Courses</title>
</head>
<body>
    <h1>Available Courses</h1>
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Description</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo $row['course_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>