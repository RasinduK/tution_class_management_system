<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

// Define variables and initialize with empty values
$month = $batch = "";
$month_err = $batch_err = "";
$attendance_records = [];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate month
    if(empty(trim($_POST["month"]))){
        $month_err = "Please select a month.";
    } else{
        $month = trim($_POST["month"]);
    }

    // Validate batch
    if(empty(trim($_POST["batch"]))){
        $batch_err = "Please select a batch.";
    } else{
        $batch = trim($_POST["batch"]);
    }

    // Get attendance records if there are no errors
    if (empty($month_err) && empty($batch_err)) {
        $sql = "SELECT students.full_name, attendance.date, attendance.status 
                FROM attendance 
                JOIN students ON attendance.student_id = students.id 
                WHERE students.batch = ? AND DATE_FORMAT(attendance.date, '%Y-%m') = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $batch, $month);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $attendance_records[] = $row;
                }
            } else {
                echo "Error executing query: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<div class="navbar">
        <a href="dashboard.php">Home</a>
        <a href="student.php">Student</a>
        <a href="attendnce.php">Attendnce</a>
        <!--<a href="Payment.php">Payment</a>
        <a href="study_material.php">Study Material</a>
        <a href="exam.php">Exam</a>
        <a href="result.php">Result</a>-->
        <a href="dashboard.php" class="logout-button">Back</a>
    </div>
    <div>
        <h2>View Attendance</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label>Select Month</label>
            <input type="month" name="month" value="<?php echo $month; ?>" required>
            <span class="error"><?php echo $month_err; ?></span><br>

            <label>Select Batch</label>
            <select name="batch" required>
                <option value="">Select</option>
                <option value="2024" <?php if($batch == "2024") echo "selected"; ?>>2024</option>
                <option value="2025" <?php if($batch == "2025") echo "selected"; ?>>2025</option>
                <option value="2026" <?php if($batch == "2026") echo "selected"; ?>>2026</option>
                <option value="2027" <?php if($batch == "2027") echo "selected"; ?>>2027</option>
            </select>
            <span class="error"><?php echo $batch_err; ?></span><br>

            <input type="submit" value="View Attendance">
        </form>
        <?php if (!empty($attendance_records)): ?>
            <h3>Attendance Records for <?php echo $batch; ?> Batch in <?php echo date("F Y", strtotime($month . "-01")); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance_records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['full_name']); ?></td>
                            <td><?php echo htmlspecialchars(date("Y-m-d", strtotime($record['date']))); ?></td>
                            <td><?php echo $record['status'] ? 'Present' : 'Absent'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
