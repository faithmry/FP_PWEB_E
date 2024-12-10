<?php
include '../includes/db_connection.php';

$student_id = 1; // Ganti dengan ID siswa yang sedang login
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : 0;

$sql = "SELECT Attendance.date, Subject.subject_name, Attendance.status FROM Attendance
        JOIN Subject ON Attendance.subject_id = Subject.subject_id
        WHERE Attendance.student_id = ? AND Attendance.course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $student_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<html>
<head>
    <title>View Attendance</title>
</head>
<body>
    <h1>Attendance Records</h1>
    <table border="1">
        <tr>
            <th>Date</th>
            <th>Subject</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['subject_name']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>