<?php
include '../includes/db_connection.php';

if (isset($_GET['id'])) {
    $faculty_id = $_GET['id'];

    // Fetch faculty data
    $sql = "SELECT * FROM Faculty WHERE faculty_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $faculty = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $faculty_name = $_POST['faculty_name'];
        $faculty_email = $_POST['faculty_email'];
        $phone_number = $_POST['phone_number'];

        $update_sql = "UPDATE Faculty SET faculty_name = ?, faculty_email = ?, phone_number = ? WHERE faculty_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('sssi', $faculty_name, $faculty_email, $phone_number, $faculty_id);
        $update_stmt->execute();

        header('Location: manage_faculty.php');
        exit();
    }
} else {
    echo "Faculty ID not specified.";
    exit();
}
?>
<html>
<head>
    <title>Edit Faculty</title>
</head>
<body>
    <h1>Edit Faculty</h1>
    <form method="POST" action="">
        <label for="faculty_name">Faculty Name:</label>
        <input type="text" name="faculty_name" value="<?php echo htmlspecialchars($faculty['faculty_name']); ?>" required>
        <br>
        <label for="faculty_email">Email:</label>
        <input type="email" name="faculty_email" value="<?php echo htmlspecialchars($faculty['faculty_email']); ?>" required>
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($faculty['phone_number']); ?>">
        <br>
        <button type="submit">Update Faculty</button>
    </form>
</body>
</html>