<?php
require_once "connection.php";

if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $query = "SELECT full_name FROM students WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stmt->bind_result($full_name);
        $stmt->fetch();
        echo $full_name;
        $stmt->close();
    }
}
?>
