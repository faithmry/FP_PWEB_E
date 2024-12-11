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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100%;
            background: linear-gradient(135deg, #D29A6A, #8E6E4B);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 60px;
            border-radius: 25px;
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 800px;
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #4E342E;
        }

        h2 {
            font-size: 30px;
            margin-bottom: 40px;
            color: #5D4037;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin: 15px 0;
            font-size: 20px;
            font-weight: bold;
        }

        a {
            text-decoration: none;
            color: #8E6E4B;
            padding: 15px 25px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            display: inline-block;
        }

        a:hover {
            background-color: #8E6E4B;
            color: #fff;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
            transform: translateY(-5px);
        }

        .message {
            margin-top: 20px;
            font-size: 18px;
            color: #E74C3C;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 30px 40px;
            }
            h1 {
                font-size: 36px;
            }
            h2 {
                font-size: 24px;
            }
            a {
                font-size: 18px;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
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
    </div>
</body>
</html>
