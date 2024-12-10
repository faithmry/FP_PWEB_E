<?php
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $faculty_name = $_POST['faculty_name'];
    $faculty_email = $_POST['faculty_email'];
    $phone_number = $_POST['phone_number'];

    $sql = "INSERT INTO Faculty (faculty_name, faculty_email, phone_number) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $faculty_name, $faculty_email, $phone_number);
    $stmt->execute();

    header('Location: manage_faculty.php');
    exit();
}
?>
<html>
<head>
    <title>Add Faculty</title>
</head>
<body>
    <h1>Add New Faculty</h1>
    <form method="POST" action="">
        <label for="faculty_name">Faculty Name:</label>
        <input type="text" name="faculty_name" required>
        <br>
        <label for="faculty_email">Email:</label>
        <input type="email" name="faculty_email" required>
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number">
        <br>
        <button type="submit">Add Faculty</button>
    </form>
</body>
</html>