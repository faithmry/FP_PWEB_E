<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek di tabel Admin
    $sql_admin = "SELECT * FROM Admin WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql_admin);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result_admin = $stmt->get_result();

    if ($result_admin->num_rows > 0) {
        // Login sebagai Admin
        header('Location: ../views/admin_dashboard.php');
        exit();
    }

    // Cek di tabel Lecturer
    $sql_lecturer = "SELECT * FROM Lecturer WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql_lecturer);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result_lecturer = $stmt->get_result();

    if ($result_lecturer->num_rows > 0) {
        // Login sebagai Lecturer
        header('Location: ../views/lecturer_dashboard.php');
        exit();
    }
    
    // Cek di tabel Student
    $sql_student = "SELECT * FROM Student WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql_student);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result_student = $stmt->get_result();

    if ($result_student->num_rows > 0) {
        // Login sebagai Student
        header('Location: ../views/student_dashboard.php');
        exit();
    }

    // Jika gagal
    echo "Login gagal. Periksa email dan password Anda.";
}
?>