<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

if (isset($_GET["st_no"])) {
    $st_no = $_GET["st_no"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = "DELETE FROM students WHERE st_no = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $st_no);

            if ($stmt->execute()) {
                header("Location: view_student.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }

        $conn->close();
    }
} else {
    header("Location: view_student.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
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
        <a href="dashboard.php" class="logout-button">back</a>
    </div>
    <h2>Delete Student</h2>
    <p>Are you sure you want to delete this student record?</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?st_no=<?php echo $_GET["st_no"]; ?>" method="post">
        <input type="submit" value="Yes">
        <button onclick="location.href='view_student.php'">No</button>
    </form>
</body>
</html>
