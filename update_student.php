<?php
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: loging.php");
    exit;
}

// Include the database connection file
require_once "connection.php";

// Define variables and initialize with empty values
$st_no = $full_name = $address = $school = $grade = $gender = $dob = $mobile_no = $batch = $category = "";
$gender_err = "";

if (isset($_GET["st_no"])) {
    $st_no = $_GET["st_no"];

    // Fetch the existing data for the student
    $sql = "SELECT * FROM students WHERE st_no = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $st_no);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $full_name = $row["full_name"];
                $address = $row["address"];
                $school = $row["school"];
                $grade = $row["grade"];
                $gender = $row["gender"];
                $dob = $row["dob"];
                $mobile_no = $row["mobile_no"];
                $batch = $row["batch"];
                $category = $row["category"];
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $st_no = trim($_POST["st_no"]);
    $full_name = trim($_POST["full_name"]);
    $address = trim($_POST["address"]);
    $school = trim($_POST["school"]);
    $grade = trim($_POST["grade"]);
    $gender = trim($_POST["gender"]);
    $dob = trim($_POST["dob"]);
    $mobile_no = trim($_POST["mobile_no"]);
    $batch = trim($_POST["batch"]);
    $category = trim($_POST["category"]);

    // Check if gender is selected
    if (empty($gender)) {
        $gender_err = "Please select a gender.";
    }

    // Update data into database if there are no errors
    if (empty($gender_err)) {
        $sql = "UPDATE students SET full_name=?, address=?, school=?, grade=?, gender=?, dob=?, mobile_no=?, batch=?, category=? WHERE st_no=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssssss", $full_name, $address, $school, $grade, $gender, $dob, $mobile_no, $batch, $category, $st_no);

            if ($stmt->execute()) {
                // Redirect to a success page or another page
                header("Location: view_student.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
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
    <title>Edit Student</title>
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
    <h2>Edit Student</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="st_no" value="<?php echo $st_no; ?>">

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo $full_name; ?>" required><br>

        <label>Address</label>
        <input type="text" name="address" value="<?php echo $address; ?>" required><br>

        <label>School</label>
        <input type="text" name="school" value="<?php echo $school; ?>" required><br>

        <label>Grade</label>
        <input type="text" name="grade" value="<?php echo $grade; ?>" required><br>

        <label>Gender</label>
        <select name="gender" required>
            <option value="">Select</option>
            <option value="Male" <?php if ($gender == "Male") echo "selected"; ?>>Male</option>
            <option value="Female" <?php if ($gender == "Female") echo "selected"; ?>>Female</option>
        </select><br>
        <span class="error"><?php echo $gender_err; ?></span><br>

        <label>Date of Birth</label>
        <input type="date" name="dob" value="<?php echo $dob; ?>" required><br>

        <label>Mobile No</label>
        <input type="text" name="mobile_no" value="<?php echo $mobile_no; ?>" required><br>

        <label>Batch</label>
        <input type="text" name="batch" value="<?php echo $batch; ?>" required><br>

        <label>Category</label>
        <input type="text" name="category" value="<?php echo $category; ?>" required><br>

        <input type="submit" value="Save Changes">
    </form>

    <button onclick="location.href='view_student.php'">Back</button>
</body>
</html>
