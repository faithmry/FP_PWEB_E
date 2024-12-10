<?php
include '../includes/db_connection.php';

$lecturer_id = 1; // Ganti dengan ID dosen yang sedang login

// Ambil data kursus yang diajar oleh dosen
$sql_courses = "SELECT Course.course_id, Course.course_name FROM Course
                JOIN LecturerCourse ON Course.course_id = LecturerCourse.course_id
                WHERE LecturerCourse.lecturer_id = ?";
$stmt = $conn->prepare($sql_courses);
$stmt->bind_param('i', $lecturer_id);
$stmt->execute();
$result_courses = $stmt->get_result();
?>
<html>
<head>
    <title>Lecturer Dashboard</title>
</head>
<body>
    <h1>Welcome, Lecturer</h1>
    <h2>Courses You Teach</h2>
    <ul>
        <?php while ($course = $result_courses->fetch_assoc()) { ?>
        <li>
            <a href="course_detail_lecturer.php?id=<?php echo $course['course_id']; ?>">
                <?php echo $course['course_name']; ?>
            </a>
        </li>
        <?php } ?>
    </ul>
</body>
</html>