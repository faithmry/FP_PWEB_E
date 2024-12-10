<?php
include '../includes/db_connection.php';

// Fetch marks data
$sql = "SELECT Marks.*, Subject.subject_name FROM Marks 
        JOIN Subject ON Marks.subject_id = Subject.subject_id
        WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$student_id = 1; // Ganti dengan ID mahasiswa yang sedang login
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<html>
<head>
    <title>View Marks</title>
</head>
<body>
    <h1>View Marks</h1>
    <table border="1">
        <tr>
            <th>Subject</th>
            <th>Marks Obtained</th>
            <th>Max Marks</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['subject_name']; ?></td>
            <td><?php echo $row['marks_obtained']; ?></td>
            <td><?php echo $row['max_marks']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
