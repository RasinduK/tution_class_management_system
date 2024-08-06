<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: loging.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

// Initialize variables
$batch = "";
$st_no = "";
$results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $batch = trim($_POST["batch"]);
    $st_no = trim($_POST["st_no"]);

    // Prepare the SQL query based on input
    $sql = "SELECT * FROM students WHERE 1=1";
    if (!empty($batch)) {
        $sql .= " AND batch = ?";
    }
    if (!empty($st_no)) {
        $sql .= " AND st_no = ?";
    }

    if ($stmt = $conn->prepare($sql)) {
        if (!empty($batch) && !empty($st_no)) {
            $stmt->bind_param("ss", $batch, $st_no);
        } elseif (!empty($batch)) {
            $stmt->bind_param("s", $batch);
        } elseif (!empty($st_no)) {
            $stmt->bind_param("s", $st_no);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $results = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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
        <a href="dashboard.php" class="logout-button">Back</a>
    </div>
    <h2>View Student Records</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Select Batch</label>
        <select name="batch">
            <option value="">Select</option>
            <option value="2024" <?php if ($batch == "2024") echo "selected"; ?>>2024</option>
            <option value="2025" <?php if ($batch == "2025") echo "selected"; ?>>2025</option>
            <option value="2026" <?php if ($batch == "2026") echo "selected"; ?>>2026</option>
            <option value="2027" <?php if ($batch == "2027") echo "selected"; ?>>2027</option>
        </select><br>

        <label>Search by Student Number</label>
        <input type="text" name="st_no" value="<?php echo $st_no; ?>"><br>

        <input type="submit" value="Load">
    </form>

    <?php if (!empty($results)): ?>
    <table>
        <thead>
            <tr>
                <th>St No</th>
                <th>Full Name</th>
                <th>Address</th>
                <th>School</th>
                <th>Grade</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Mobile No</th>
                <th>Batch</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["st_no"]); ?></td>
                <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["address"]); ?></td>
                <td><?php echo htmlspecialchars($row["school"]); ?></td>
                <td><?php echo htmlspecialchars($row["grade"]); ?></td>
                <td><?php echo htmlspecialchars($row["gender"]); ?></td>
                <td><?php echo htmlspecialchars($row["dob"]); ?></td>
                <td><?php echo htmlspecialchars($row["mobile_no"]); ?></td>
                <td><?php echo htmlspecialchars($row["batch"]); ?></td>
                <td><?php echo htmlspecialchars($row["category"]); ?></td>
                <td>
                    <a href="update_student.php?st_no=<?php echo $row['st_no']; ?>">Edit</a>
                    <a  href="delete_student.php?st_no=<?php echo $row['st_no']; ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>No records found.</p>
    <?php endif; ?>

    <!--<button onclick="location.href='dashboard.php'">Back</button>-->
</body>
</html>
