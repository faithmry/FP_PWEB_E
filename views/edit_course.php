<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Fetch course data
    $sql = "SELECT * FROM Course WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $course_name = $_POST['course_name'];
        $description = $_POST['description'];

        $update_sql = "UPDATE Course SET course_name = ?, description = ? WHERE course_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('ssi', $course_name, $description, $course_id);
        $update_stmt->execute();

        header('Location: manage_courses.php');
        exit();
    }
} else {
    echo "Course ID not specified.";
    exit();
}
?>
<html>
<head>
    <title>Edit Course</title>
</head>
<body>
    <h1>Edit Course</h1>
    <form method="POST" action="">
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($course['description']); ?></textarea>
        <br>
        <button type="submit">Update Course</button>
    </form>
</body>
</html>