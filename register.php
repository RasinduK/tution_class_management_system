<?php
session_start();

// Include the database connection file
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['reg_uname'];
    $password = $_POST['reg_pwd'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Username already exists. Please choose a different username.";
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            // Registration successful
            header("Location: loging.php");
            exit;
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
        <a href="loging.php">Home</a>
        <!--<a href="student.php">Student</a>
        <a href="attendnce.php">Attendnce</a>
        <a href="Payment.php">Payment</a>
        <a href="study_material.php">Study Material</a>
        <a href="exam.php">Exam</a>
        <a href="result.php">Result</a>-->
        <a href="logout.php" class="logout-button">Exit</a>
    </div>
    <div>
        <h1>Create Account</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="reg_uname">User Name:</label>
            <input type="text" id="reg_uname" name="reg_uname" required>
            
            <label for="reg_pwd">Password:</label>
            <input type="password" id="reg_pwd" name="reg_pwd" required>
            
            <button type="submit">Create Account</button>
        </form>
        <?php if (isset($error_message)) { echo '<p class="error">' . $error_message . '</p>'; } ?>
        <p>Already have an account? <a href="loging.php">Sign in here</a>.</p>
    </div>
</body>
</html>

