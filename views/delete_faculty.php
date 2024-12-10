<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $faculty_id = $_GET['id'];

    $sql = "DELETE FROM Faculty WHERE faculty_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $faculty_id);
    $stmt->execute();

    header('Location: manage_faculty.php');
    exit();
} else {
    echo "Faculty ID not specified.";
    exit();
}
?>