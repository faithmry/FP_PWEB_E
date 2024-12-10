<?php
include '../includes/db_connection.php';

if (isset($_GET['student_id']) && isset($_GET['course_id'])) {
    $student_id = $_GET['student_id'];
    $course_id = $_GET['course_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST['date'];
        $status = $_POST['status'];

        $sql = "INSERT INTO Attendance (student_id, course_id, date, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iiss', $student_id, $course_id, $date, $status);
        $stmt->execute();

        echo "Attendance marked successfully!";
    }
}
?>
<html>
<head>
    <title>Mark Attendance</title>
</head>
<body>
    <h1>Mark Attendance for Student ID: <?php echo $student_id; ?></h1>
    <form method="POST" action="">
        <label for="date">Date:</label>
        <input type="date" name="date" required>
        <br>
        <label for="status">Status:</label>
        <select name="status">
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Late">Late</option>
        </select>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
