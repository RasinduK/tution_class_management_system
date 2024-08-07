<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

// Additional PHP code for processing forms can be placed here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendence Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
        <a href="dashboard.php">Home</a>
        <a href="student.php">Student</a>
        <a href="attendnce.php">Attendnce</a>
        <a href="Payment.php">Payment</a>
        <!--<a href="study_material.php">Study Material</a>
        <a href="exam.php">Exam</a>
        <a href="result.php">Result</a>-->
        <a href="dashboard.php" class="logout-button">Back</a>
    </div>
    <main>
        <section class="actions">
        <div class="card">
            <img src="icons/add_icon.png" alt="Add Record">
            <h2>Add Attendence</h2>
            <!--<p>Admission of new student</p>-->
            <button onclick="location.href='add_attendence.php'">Add</button>
        </div>
        <div class="card">
            <img src="icons/view_icon.png" alt="View Record">
            <h2>View Attendence</h2>
            <!--<p>To view, edit, search, and delete student records</p>-->
            <button onclick="location.href='view_attendance.php'">Access Now</button>
        </div>
</section>
</main>
</body>
</html>




