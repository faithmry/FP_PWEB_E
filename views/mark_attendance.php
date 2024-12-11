<?php
include '../includes/db_connection.php';
session_start();

// Pastikan lecturer_id ada di session (untuk keamanan, hanya dosen bisa mengakses)
if (!isset($_SESSION['lecturer_id'])) {
    header("Location: ../views/login.php");
    exit();
}

$notification = ''; // Variable for the notification message

if (isset($_GET['student_id']) && isset($_GET['course_id'])) {
    $student_id = $_GET['student_id'];
    $course_id = $_GET['course_id'];

    // Handle form submission for new attendance or updating existing attendance
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST['date'];
        $status = $_POST['status'];

        if (isset($_POST['attendance_id'])) {
            // Update attendance
            $attendance_id = $_POST['attendance_id'];
            $sql_update = "UPDATE Attendance SET date = ?, status = ? WHERE attendance_id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param('ssi', $date, $status, $attendance_id);
            $stmt->execute();
            $notification = "Attendance updated successfully!";
        } else {
            // Insert new attendance
            $sql_insert = "INSERT INTO Attendance (student_id, subject_id, date, status) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param('iiss', $student_id, $course_id, $date, $status);
            $stmt->execute();
            $notification = "Attendance marked successfully!";
        }
    }

    if (isset($_GET['delete'])) {
        // Delete attendance
        $attendance_id = $_GET['delete'];
        $sql_delete = "DELETE FROM Attendance WHERE attendance_id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param('i', $attendance_id);
        $stmt->execute();
        $notification = "Attendance deleted successfully!";
    }

    // Fetch all attendance records for the student in this course
    $sql_attendance = "SELECT attendance_id, date, status FROM Attendance WHERE student_id = ? AND subject_id = ?";
    $stmt = $conn->prepare($sql_attendance);
    $stmt->bind_param('ii', $student_id, $course_id);
    $stmt->execute();
    $attendance_result = $stmt->get_result();
}
?>
<html>
<head>
    <title>Mark Attendance</title>
</head>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #D29A6A, #8E6E4B);
        color: #5D4037;
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }

    .notification {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        border-radius: 8px;
        display: <?php echo $notification ? 'block' : 'none'; ?>;
    }

    .container {
        max-width: 900px;
        margin: 50px auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #5D4037;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    label {
        font-size: 16px;
    }

    input, select, button {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        background-color: #5D4037;
        color: white;
        cursor: pointer;
        border: none;
        width: 75px;
        height: 40px; 
    }

    button:hover {
        background-color: #4E342E;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 10px;
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

    .actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .actions form {
        display: inline;
    }

    .actions a {
    display: inline-block;
    padding: 8px 16px; 
    font-size: 16px; 
    background-color: #FF5733; 
    color: white;
    border-radius: 4px;
    text-decoration: none;
    text-align: center;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.actions a:hover {
    background-color: #C1351D;
    transform: scale(1.05); 
}


</style>

<div class="notification">
    <?php echo htmlspecialchars($notification); ?>
</div>

<div class="container">
    <h2>Mark New Attendance</h2>
    <form method="POST" action="">
        <label for="date">Date:</label>
        <input type="date" name="date" required>
        
        <label for="status">Status:</label>
        <select name="status">
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
            <option value="Late">Late</option>
        </select>
        
        <button type="submit">Submit</button>
    </form>

    <h2>Attendance Records</h2>
    <?php if ($attendance_result->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($attendance = $attendance_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($attendance['date']); ?></td>
                <td><?php echo htmlspecialchars($attendance['status']); ?></td>
                <td class="actions">
                    <!-- Update Form -->
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="attendance_id" value="<?php echo $attendance['attendance_id']; ?>">
                        <input type="date" name="date" value="<?php echo $attendance['date']; ?>" required>
                        <select name="status">
                            <option value="Present" <?php if ($attendance['status'] == 'Present') echo 'selected'; ?>>Present</option>
                            <option value="Absent" <?php if ($attendance['status'] == 'Absent') echo 'selected'; ?>>Absent</option>
                            <option value="Late" <?php if ($attendance['status'] == 'Late') echo 'selected'; ?>>Late</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>

                    <!-- Delete Link -->
                    <button href="?student_id=<?php echo $student_id; ?>&course_id=<?php echo $course_id; ?>&delete=<?php echo $attendance['attendance_id']; ?>"
                       onclick="return confirm('Are you sure you want to delete this attendance?');">Delete</button>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No attendance records found for this student.</p>
    <?php } ?>
</div>

</html>
