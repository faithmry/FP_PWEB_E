<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $assignment_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_id = 1; // Ganti dengan ID siswa yang sedang login
        $file_path = 'uploads/' . basename($_FILES['assignment_file']['name']);
        move_uploaded_file($_FILES['assignment_file']['tmp_name'], $file_path);

        $sql = "INSERT INTO AssignmentSubmission (assignment_id, student_id, file_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $assignment_id, $student_id, $file_path);
        $stmt->execute();

        echo "Submission successful!";
    }
}
?>
<html>
<head>
    <title>Submit Assignment</title>
</head>
<body>
    <h1>Submit Your Assignment</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="assignment_file">Upload File:</label>
        <input type="file" name="assignment_file" required>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>