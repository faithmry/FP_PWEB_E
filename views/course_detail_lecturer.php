<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Ambil data tugas yang ada di kursus ini
    $sql_assignments = "SELECT * FROM Assignment WHERE course_id = ?";
    $stmt = $conn->prepare($sql_assignments);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $assignments_result = $stmt->get_result();

    // Ambil data siswa yang mengikuti kursus ini
    $sql_students = "SELECT Student.student_id, Student.student_name FROM Student
                     JOIN StudentCourse ON Student.student_id = StudentCourse.student_id
                     WHERE StudentCourse.course_id = ?";
    $stmt = $conn->prepare($sql_students);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $students_result = $stmt->get_result();
}
?>
<html>
<head>
    <title>Course Details</title>
</head>
<body>
    <h1>Course Details</h1>
    <h2>Assignments</h2>
    <ul>
        <?php while ($assignment = $assignments_result->fetch_assoc()) { ?>
        <li>
            <?php echo $assignment['assignment_name']; ?> (Due Date: <?php echo $assignment['due_date']; ?>)
            <a href="grade_assignment.php?id=<?php echo $assignment['assignment_id']; ?>">Grade Assignment</a>
        </li>
        <?php } ?>
    </ul>

    <h2>Students in This Course</h2>
    <ul>
        <?php while ($student = $students_result->fetch_assoc()) { ?>
        <li>
            <?php echo $student['student_name']; ?>
            <a href="mark_attendance.php?student_id=<?php echo $student['student_id']; ?>&course_id=<?php echo $course_id; ?>">Mark Attendance</a>
        </li>
        <?php } ?>
    </ul>
</body>
</html>