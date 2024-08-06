<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

// Additional PHP code for fetching data from the database can be placed here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">     
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Home</a>
        <a href="student.php">Student</a>
        <a href="attendnce.php">Attendnce</a>
        <!--<a href="Payment.php">Payment</a>
        <a href="study_material.php">Study Material</a>
        <a href="exam.php">Exam</a>
        <a href="result.php">Result</a>-->
        <a href="logout.php" class="logout-button">Exit</a>
    </div>
<main>
    <div class="content">
        <h1>Welcome to the Dashboard</h1>
        <p>Gamunu Geography</P>
    </div>
    <div class="header">
        <div class="date-time">
            <span id="current-date"></span>
            <span id="current-time"></span>
        </div>
    </div>
    <main>
    <script>
        function updateTime() {
            const now = new Date();
            const date = now.toLocaleDateString();
            const time = now.toLocaleTimeString();
            document.getElementById('current-date').innerText = date;
            document.getElementById('current-time').innerText = time;
        }

        setInterval(updateTime, 1000);
        updateTime(); // initial call
    </script>
</body>
</html>
