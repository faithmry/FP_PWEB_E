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

    // Cek apakah data assignment ditemukan
    if (!$assignment) {
        die("Assignment not found.");
    }

    // Ambil data submissions
    $sql_submissions = "SELECT * FROM AssignmentSubmission WHERE assignment_id = ?";
    $stmt = $conn->prepare($sql_submissions);
    $stmt->bind_param('i', $assignment_id);
    $stmt->execute();
    $submissions_result = $stmt->get_result();
    

    // Ambil data nilai termasuk nama siswa
    $sql_scores = "SELECT asg.score_id, asg.student_id, asg.score, asn.file_path, s.student_name 
                   FROM AssignmentScore asg
                   LEFT JOIN AssignmentSubmission asn 
                   ON asg.assignment_id = asn.assignment_id AND asg.student_id = asn.student_id
                   LEFT JOIN Student s ON asg.student_id = s.student_id
                   WHERE asg.assignment_id = ?";
    $stmt = $conn->prepare($sql_scores);
    $stmt->bind_param('i', $assignment_id);
    $stmt->execute();
    $scores_result = $stmt->get_result();
    $scores = $scores_result->fetch_all(MYSQLI_ASSOC);

    // Tambah atau update nilai
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        $action = $_POST['action'];
        $score_id = isset($_POST['score_id']) ? $_POST['score_id'] : null;

        if ($action === 'create' || $action === 'update') {
            $student_id = $_POST['student_id'];
            $score = $_POST['score'];

            if (!is_numeric($score) || $score < 0) {
                die("Invalid score. Score must be a positive number.");
            }

            if ($action === 'create') {
                $sql_score = "INSERT INTO AssignmentScore (assignment_id, student_id, score) 
                              VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql_score);
                $stmt->bind_param('iid', $assignment_id, $student_id, $score);
            } else {
                $sql_score = "UPDATE AssignmentScore SET score = ? WHERE score_id = ?";
                $stmt = $conn->prepare($sql_score);
                $stmt->bind_param('di', $score, $score_id);
            }

            if ($stmt->execute()) {
                echo "Score saved successfully!";
            } else {
                echo "Error saving score: " . $conn->error;
            }
        } elseif ($action === 'delete') {
            $sql_delete = "DELETE FROM AssignmentScore WHERE score_id = ?";
            $stmt = $conn->prepare($sql_delete);
            $stmt->bind_param('i', $score_id);
            if ($stmt->execute()) {
                echo "Score deleted successfully!";
            } else {
                echo "Error deleting score: " . $conn->error;
            }
        }
    }
} else {
    die("Assignment ID not provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Assignment</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #D29A6A, #8E6E4B);
        color: #5D4037;
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }

    .container {
        max-width: 900px;
        margin: 50px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h1, h2 {
        color: #5D4037;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    table th {
        background-color: #5D4037;
        color: white;
    }

    a {
        color: #5D4037;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .form-container {
        margin-bottom: 30px;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    input, button {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        background-color: #5D4037;
        color: white;
        cursor: pointer;
    }

    button:hover {
        background-color: #4E342E;
    }

    .actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .actions form {
        display: inline;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>Grade Assignment: <?php echo htmlspecialchars($assignment['assignment_name']); ?></h1>

        <div class="form-container">
            <h2>Add or Update Score</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="create">
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" required>
                <label for="score">Score:</label>
                <input type="number" name="score" step="0.01" required>
                <button type="submit">Submit Score</button>
            </form>
        </div>

        <h2>Submissions</h2>
        <?php if ($submissions_result->num_rows === 0) { ?>
            <p>No submissions found for this assignment.</p>
        <?php } else { ?>
            <ul>
                <?php while ($submission = $submissions_result->fetch_assoc()) { 
                    $student_id = $submission['student_id'];
                    $sql_student_name = "SELECT student_name FROM Student WHERE student_id = ?";
                    $stmt = $conn->prepare($sql_student_name);
                    $stmt->bind_param('i', $student_id);
                    $stmt->execute();
                    $student_result = $stmt->get_result();
                    $student = $student_result->fetch_assoc();
                ?>
                <li>
                    (<?php echo htmlspecialchars($student['student_name']); ?>):
                    Student ID: <?php echo htmlspecialchars($submission['student_id']); ?>,
                    <a href="<?php echo htmlspecialchars($submission['file_path']); ?>" target="_blank">View File</a>
                </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <h2>Assignment Scores</h2>
        <?php if (empty($scores)) { ?>
            <p>No scores found for this assignment.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Score</th>
                        <th>File Path</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $score) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($score['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($score['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($score['score']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($score['file_path']); ?>" target="_blank">View File</a></td>
                        <td>
                            <!-- Edit Form -->
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="score_id" value="<?php echo htmlspecialchars($score['score_id']); ?>">
                                <input type="number" name="score" step="0.01" value="<?php echo htmlspecialchars($score['score']); ?>" required>
                                <button type="submit">Update</button>
                            </form>

                            <!-- Delete Form -->
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="score_id" value="<?php echo htmlspecialchars($score['score_id']); ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this score?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</body>
</html>

