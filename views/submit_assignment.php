<?php
include '../includes/db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if (isset($_GET['id'])) {
    $assignment_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle form submit or update
        $file_path = 'uploads/' . basename($_FILES['assignment_file']['name']);
        move_uploaded_file($_FILES['assignment_file']['tmp_name'], $file_path);

        if (isset($_POST['action']) && $_POST['action'] === 'update') {
            // Update submission
            $submission_id = $_POST['submission_id'];
            $sql_update = "UPDATE AssignmentSubmission SET file_path = ? WHERE submission_id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param('si', $file_path, $submission_id);
            $stmt->execute();
            echo "Submission updated successfully!";
        } else {
            // Create new submission
            $sql_insert = "INSERT INTO AssignmentSubmission (assignment_id, student_id, file_path) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param('iis', $assignment_id, $student_id, $file_path);
            $stmt->execute();
            echo "Submission successful!";
        }
    }

    if (isset($_GET['delete'])) {
        // Delete submission
        $submission_id = $_GET['delete'];
        $sql_delete = "DELETE FROM AssignmentSubmission WHERE submission_id = ? AND student_id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param('ii', $submission_id, $student_id);
        $stmt->execute();
        echo "Submission deleted successfully!";
    }

    // Fetch all submissions for the logged-in student
    $sql_submissions = "SELECT submission_id, file_path FROM AssignmentSubmission WHERE assignment_id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql_submissions);
    $stmt->bind_param('ii', $assignment_id, $student_id);
    $stmt->execute();
    $submissions_result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Roboto:wght@400;500;700&family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Lora', serif; /* Professional serif font for body text */
            background: linear-gradient(135deg, #D29A6A, #8E6E4B);
            color: #4E342E; /* Dark professional brown for main text */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 40px;
        }
        h1, h2 {
            text-align: center;
            color: #E9DAC0; /* Deep brown for headings */
            font-family: 'Roboto Slab', serif; /* Professional slab-serif font for headings */
            font-weight: 700;
            margin-bottom: 20px;
        }
        form {
            font-family: 'Roboto', sans-serif; /* Clean sans-serif font for forms */
            background-color: #F5F5F5; /* Light gray background for form */
            padding: 20px;
            border: 1px solid #E0E0E0; /* Light border for a clean form */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        label {
            font-family: 'Open Sans', sans-serif; /* Clear sans-serif font for labels */
            color: #6D4C41; /* Professional brown for labels */
            font-size: 14px;
        }
        input[type="file"] {
            margin: 10px 0;
            padding: 8px;
            border-radius: 4px;
            background-color: #BCAAA4; /* Muted brown for input fields */
            color: #FFF;
            border: none;
        }
        button {
            background-color: #795548; /* Professional medium brown for buttons */
            color: #FFF;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
        }
        button:hover {
            background-color: #8D6E63; /* Lighter brown on hover */
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            font-family: 'Lora', serif; /* Classy serif font for submission list */
            background-color: #FAFAFA; /* Subtle light background for submission list */
            margin: 10px 0;
            padding: 15px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #E0E0E0;
        }
        li a {
            color: #4E342E; /* Dark professional brown for links */
            text-decoration: none;
        }
        li a:hover {
            text-decoration: underline;
        }
        .update-form {
            display: inline;
        }
        .delete-link {
            color: #D32F2F; /* Red for delete link to stand out */
        }
        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submit Your Assignment</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="assignment_file">Upload File:</label>
            <input type="file" name="assignment_file" required>
            <br>
            <input type="hidden" name="action" value="create">
            <button type="submit">Submit</button>
        </form>

        <h2>Your Submissions</h2>
        <ul>
            <?php while ($submission = $submissions_result->fetch_assoc()) { ?>
            <li>
                <a href="<?php echo htmlspecialchars($submission['file_path']); ?>" target="_blank">
                    View Submission
                </a>
                <form method="POST" enctype="multipart/form-data" class="update-form">
                    <input type="hidden" name="submission_id" value="<?php echo $submission['submission_id']; ?>">
                    <input type="hidden" name="action" value="update">
                    <input type="file" name="assignment_file" required>
                    <button type="submit">Update</button>
                </form>
                <a href="?id=<?php echo $assignment_id; ?>&delete=<?php echo $submission['submission_id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this submission?');" class="delete-link">
                    Delete
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
