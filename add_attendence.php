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
$date = $batch = "";
$date_err = $batch_err = "";

// Get list of students in the selected batch
$students = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate date
    if(empty(trim($_POST["date"]))){
        $date_err = "Please select a date.";
    } else{
        $date = trim($_POST["date"]);
    }

    // Validate batch
    if(empty(trim($_POST["batch"]))){
        $batch_err = "Please select a batch.";
    } else{
        $batch = trim($_POST["batch"]);
    }

    // Get students in the selected batch
    if (empty($date_err) && empty($batch_err)) {
        $sql = "SELECT id, full_name FROM students WHERE batch = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $batch);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $students[] = $row;
                }
            }
            $stmt->close();
        }
    }

    // If attendance form is submitted
    if (isset($_POST['mark_attendance'])) {
        foreach ($students as $student) {
            $student_id = $student['id'];
            $attendance_status = isset($_POST["attendance_$student_id"]) ? 1 : 0;

            // Check if attendance already exists for the date and student
            $sql = "SELECT id FROM attendance WHERE date = ? AND student_id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $date, $student_id);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows == 0) {
                    // Insert new attendance record
                    $stmt->close();
                    $sql = "INSERT INTO attendance (date, student_id, status) VALUES (?, ?, ?)";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sii", $date, $student_id, $attendance_status);
                        $stmt->execute();
                        $stmt->close();
                    }
                } else {
                    // Update existing attendance record
                    $stmt->close();
                    $sql = "UPDATE attendance SET status = ? WHERE date = ? AND student_id = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("isi", $attendance_status, $date, $student_id);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        }
        header("Location: view_attendance.php");
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Attendance</title>
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
        <a href="dashboard.php" class="logout-button">Exit</a>
    </div>
    <div>
        <h2>Add Attendance</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label>Select Date</label>
            <input type="date" name="date" value="<?php echo $date; ?>" required>
            <span class="error"><?php echo $date_err; ?></span><br>

            <label>Select Batch</label>
            <select name="batch" onchange="this.form.submit()" required>
                <option value="">Select</option>
                <option value="2024" <?php if($batch == "2024") echo "selected"; ?>>2024</option>
                <option value="2025" <?php if($batch == "2025") echo "selected"; ?>>2025</option>
                <option value="2026" <?php if($batch == "2026") echo "selected"; ?>>2026</option>
                <option value="2027" <?php if($batch == "2027") echo "selected"; ?>>2027</option>
            </select>
            <span class="error"><?php echo $batch_err; ?></span><br>

            <?php if (!empty($students)): ?>
                <h3>Mark Attendance</h3>
                <?php foreach ($students as $student): ?>
                    <label>
                        <input type="checkbox" name="attendance_<?php echo $student['id']; ?>" value="1">
                        <?php echo $student['full_name']; ?>
                    </label><br>
                <?php endforeach; ?>
                <input type="hidden" name="mark_attendance" value="1">
                <input type="submit" value="Save">
                <button type="button" onclick="location.href='attendance.php'">Back</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
