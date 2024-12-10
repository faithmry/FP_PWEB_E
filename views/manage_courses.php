<?php
include '../includes/db_connection.php';

// Fetch course data
$sql = "SELECT * FROM Course";
$result = $conn->query($sql);
?>
<html>
<head>
    <title>Manage Courses</title>
</head>
<body>
    <h1>Manage Courses</h1>
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['course_id']; ?></td>
            <td><?php echo $row['course_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
                <a href="edit_course.php?id=<?php echo $row['course_id']; ?>">Edit</a> |
                <a href="delete_course.php?id=<?php echo $row['course_id']; ?>" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="add_course.php">Add New Course</a>
</body>
</html>