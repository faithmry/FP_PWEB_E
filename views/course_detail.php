<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    $student_id = 1; // Ganti dengan ID siswa yang sedang login

    // Ambil data kursus
    $sql_course = "SELECT * FROM Course WHERE course_id = ?";
    $stmt = $conn->prepare($sql_course);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $course_result = $stmt->get_result();
    $course = $course_result->fetch_assoc();

    // Ambil data dosen yang mengajar kursus ini
    $sql_lecturer = "SELECT Lecturer.lecturer_name, Lecturer.email FROM Lecturer
                     JOIN LecturerCourse ON Lecturer.lecturer_id = LecturerCourse.lecturer_id
                     WHERE LecturerCourse.course_id = ?";
    $stmt = $conn->prepare($sql_lecturer);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $lecturer_result = $stmt->get_result();

    // Ambil data tugas yang ada di kursus ini
    $sql_assignments = "SELECT * FROM Assignment WHERE course_id = ?";
    $stmt = $conn->prepare($sql_assignments);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $assignments_result = $stmt->get_result();

    // Ambil data ujian yang ada di kursus ini
    $sql_exams = "SELECT * FROM Exam WHERE course_id = ?";
    $stmt = $conn->prepare($sql_exams);
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $exams_result = $stmt->get_result();
}
?>
<html>
<head>
    <title>Course Details</title>
</head>
<body>
    <h1>Course Details</h1>
    <h2>Course Name: <?php echo htmlspecialchars($course['course_name']); ?></h2>
    <p>Description: <?php echo htmlspecialchars($course['description']); ?></p>

    <h2>Lecturer Information</h2>
    <?php while ($lecturer = $lecturer_result->fetch_assoc()) { ?>
    <p>Name: <?php echo htmlspecialchars($lecturer['lecturer_name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($lecturer['email']); ?></p>
    <?php } ?>

    <h2>Assignments</h2>
    <ul>
        <?php while ($assignment = $assignments_result->fetch_assoc()) { ?>
        <li>
            <?php echo htmlspecialchars($assignment['assignment_name']); ?> (Due Date: <?php echo $assignment['due_date']; ?>)
            <a href="submit_assignment.php?id=<?php echo $assignment['assignment_id']; ?>">Submit</a>
        </li>
        <?php } ?>
    </ul>

    <h2>Exams</h2>
    <ul>
        <?php while ($exam = $exams_result->fetch_assoc()) { ?>
        <li><?php echo htmlspecialchars($exam['exam_name']); ?> (Date: <?php echo $exam['exam_date']; ?>)</li>
        <?php } ?>
    </ul>
</body>
</html>
