<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $assignment_id = $_GET['id'];

    // Ambil data assignment
    $sql_assignment = "SELECT * FROM Assignment WHERE assignment_id = ?";
    $stmt = $conn->prepare($sql_assignment);
    $stmt->bind_param('i', $assignment_id);
    $stmt->execute();
    $assignment_result = $stmt->get_result();
    $assignment = $assignment_result->fetch_assoc();

    // Ambil data submission dari siswa
    $sql_submissions = "SELECT * FROM AssignmentSubmission WHERE assignment_id = ?";
    $stmt = $conn->prepare($sql_submissions);
    $stmt->bind_param('i', $assignment_id);
    $stmt->execute();
    $submissions_result = $stmt->get_result();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_id = $_POST['student_id'];
        $score = $_POST['score'];

        // Update nilai
        $sql_score = "INSERT INTO AssignmentScore (assignment_id, student_id, score) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE score = ?";
        $stmt = $conn->prepare($sql_score);
        $stmt->bind_param('iidi', $assignment_id, $student_id, $score, $score);
        $stmt->execute();

        echo "Score updated successfully!";
    }
}
?>
<html>
<head>
    <title>Grade Assignment</title>
</head>
<body>
    <h1>Grade Assignment: <?php echo $assignment['assignment_name']; ?></h1>
    <form method="POST" action="">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" required>
        <br>
        <label for="score">Score:</label>
        <input type="number" name="score" step="0.01" required>
        <br>
        <button type="submit">Submit Score</button>
    </form>

    <h2>Submissions</h2>
    <ul>
        <?php while ($submission = $submissions_result->fetch_assoc()) { ?>
        <li>
            Student ID: <?php echo $submission['student_id']; ?>, File Path: <?php echo $submission['file_path']; ?>
        </li>
        <?php } ?>
    </ul>
</body>
</html>