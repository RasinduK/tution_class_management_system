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
$st_no = $full_name = $address = $school = $grade = $gender = $dob = $mobile_no = $batch = $category = "";
$st_no_err = $full_name_err = $address_err = $school_err = $grade_err = $gender_err = $dob_err = $mobile_no_err = $batch_err = $category_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate st_no
    if(empty(trim($_POST["st_no"]))){
        $st_no_err = "Please enter a student number.";
    } else{
        $st_no = trim($_POST["st_no"]);
    }

    // Validate full_name
    if(empty(trim($_POST["full_name"]))){
        $full_name_err = "Please enter the full name.";
    } else{
        $full_name = trim($_POST["full_name"]);
    }

    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter an address.";
    } else{
        $address = trim($_POST["address"]);
    }

    // Validate school
    if(empty(trim($_POST["school"]))){
        $school_err = "Please enter the school.";
    } else{
        $school = trim($_POST["school"]);
    }

    // Validate grade
    if(empty(trim($_POST["grade"]))){
        $grade_err = "Please enter the grade.";
    } else{
        $grade = trim($_POST["grade"]);
    }

    // Validate gender
    if(empty($_POST["gender"])){
        $gender_err = "Please select a gender.";
    } else{
        $gender = trim($_POST["gender"]);
    }

    // Validate dob
    if(empty(trim($_POST["dob"]))){
        $dob_err = "Please enter the date of birth.";
    } else{
        $dob = trim($_POST["dob"]);
    }

    // Validate mobile_no
    if(empty(trim($_POST["mobile_no"]))){
        $mobile_no_err = "Please enter a mobile number.";
    } else{
        $mobile_no = trim($_POST["mobile_no"]);
    }

    // Validate batch
    if(empty(trim($_POST["batch"]))){
        $batch_err = "Please select a batch.";
    } else{
        $batch = trim($_POST["batch"]);
    }

    // Validate category
    if(empty(trim($_POST["category"]))){
        $category_err = "Please select a category.";
    } else{
        $category = trim($_POST["category"]);
    }

    // Check input errors before inserting in database
    if(empty($st_no_err) && empty($full_name_err) && empty($address_err) && empty($school_err) && empty($grade_err) && empty($gender_err) && empty($dob_err) && empty($mobile_no_err) && empty($batch_err) && empty($category_err)){
        $sql = "INSERT INTO students (st_no, full_name, address, school, grade, gender, dob, mobile_no, batch, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssssssssss", $st_no, $full_name, $address, $school, $grade, $gender, $dob, $mobile_no, $batch, $category);

            if($stmt->execute()){
                // Redirect to view student page
                header("Location: add_student.php");
                exit;
            } else{
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
    <title>Add Student</title>
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
    <h2>Geography by Gamunu</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="width: 400px; padding: 10px;">
        <label>St No</label>
        <input type="text" name="st_no" value="<?php echo $st_no; ?>" required>
        <span><?php echo $st_no_err; ?></span><br>

        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo $full_name; ?>" required>
        <span><?php echo $full_name_err; ?></span><br>

        <label>Address</label>
        <input type="text" name="address" value="<?php echo $address; ?>" required>
        <span><?php echo $address_err; ?></span><br>

        <label>School</label>
        <input type="text" name="school" value="<?php echo $school; ?>" required>
        <span><?php echo $school_err; ?></span><br>

        <label>Grade</label>
        <input type="text" name="grade" value="<?php echo $grade; ?>" required>
        <span><?php echo $grade_err; ?></span><br>

        <label>Gender</label>
        <input type="radio" name="gender" value="Male" <?php if($gender == "Male") echo "checked"; ?> required> Male
        <input type="radio" name="gender" value="Female" <?php if($gender == "Female") echo "checked"; ?> required> Female
        <span><?php echo $gender_err; ?></span><br>

        <label>Date of Birth</label>
        <input type="date" name="dob" value="<?php echo $dob; ?>" required>
        <span><?php echo $dob_err; ?></span><br>

        <label>Mobile No</label>
        <input type="text" name="mobile_no" value="<?php echo $mobile_no; ?>" required>
        <span><?php echo $mobile_no_err; ?></span><br>

        <label>Batch</label>
        <select name="batch" required>
            <option value="">Select</option>
            <option value="2024" <?php if($batch == "2024") echo "selected"; ?>>2024</option>
            <option value="2025" <?php if($batch == "2025") echo "selected"; ?>>2025</option>
            <option value="2026" <?php if($batch == "2026") echo "selected"; ?>>2026</option>
            <option value="2027" <?php if($batch == "2027") echo "selected"; ?>>2027</option>
        </select>
        <span><?php echo $batch_err; ?></span><br>

        <label>Category</label>
        <select name="category" required>
            <option value="">Select</option>
            <option value="theory" <?php if($category == "theory") echo "selected"; ?>>Theory</option>
            <option value="revision" <?php if($category == "revision") echo "selected"; ?>>Revision</option>
            <option value="theory & revision" <?php if($category == "theory & revision") echo "selected"; ?>>Theory & Revision</option>
        </select>
        <span><?php echo $category_err; ?></span><br>

        <input type="submit" value="Save">
        <bt>
        <input type="reset" value="Cancel"  style= "width: 100%;padding: 10px;border: none;border-radius: 5px;font-size: 16px;cursor: pointer;background-color: #008CBA;color: white;">
        <button type="button" onclick="location.href='student.php'">Back</button>
    </form>
</body>
</html>
