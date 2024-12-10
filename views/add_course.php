<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO Course (course_name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $course_name, $description);
    $stmt->execute();

    header('Location: manage_courses.php');
    exit();
}
?>
<html>
<head>
    <title>Add New Course</title>
</head>
<body>
    <h1>Add New Course</h1>
    <form method="POST" action="">
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required></textarea>
        <br>
        <button type="submit">Add Course</button>
    </form>
</body>
</html>