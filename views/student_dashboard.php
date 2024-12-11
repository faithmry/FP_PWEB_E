<?php
include '../includes/db_connection.php';
session_start();

// Pastikan pengguna sudah login dan role-nya adalah student
if (!isset($_SESSION['student_id']) || $_SESSION['user_type'] !== 'student') {
    header("Location: ../views/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

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
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100%;
            background: linear-gradient(135deg, #A47551, #E9DAC0); /* Aesthetic brown and beige */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .dashboard-container {
            background: rgba(255, 248, 240, 0.95); /* Light beige background */
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #5E412F; /* Dark brown */
        }

        h2 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #8C6653; /* Medium brown */
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        li {
            margin: 0 10px;
            font-size: 18px;
            font-weight: bold;
        }

        a {
            text-decoration: none;
            color: #A47551; /* Brown shade for the links */
            padding: 10px 20px;
            background-color: #F1E1C9; /* Light beige for buttons */
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: inline-block;
        }

        a:hover {
            background-color: #A47551;
            color: #FFF3E0; /* Lighter tone on hover */
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px);
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
            color: #D84315; /* Accent brown for messages */
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px 30px;
            }
            h1 {
                font-size: 28px;
            }
            h2 {
                font-size: 20px;
            }
            a {
                font-size: 16px;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
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
    </div>
</body>
</html>
