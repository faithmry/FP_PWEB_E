<?php
include '../includes/db_connection.php';

$student_id = 1; // Ganti dengan ID siswa yang sedang login

// Ambil data kursus yang diikuti oleh siswa
$sql_courses = "SELECT Course.course_id, Course.course_name FROM Course
                JOIN StudentCourse ON Course.course_id = StudentCourse.course_id
                WHERE StudentCourse.student_id = ?";
$stmt = $conn->prepare($sql_courses);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result_courses = $stmt->get_result();
?>
<html>
<head>
    <title>Student Dashboard</title>
</head>
<body>
    <h1>Welcome to Your Courses</h1>
    <h2>Select a Course to View Details</h2>
    <ul>
        <?php while ($course = $result_courses->fetch_assoc()) { ?>
        <li>
            <a href="course_detail.php?id=<?php echo $course['course_id']; ?>">
                <?php echo $course['course_name']; ?>
            </a>
        </li>
        <?php } ?>
    </ul>
</body>
</html>